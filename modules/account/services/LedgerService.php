<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Invoice;
use app\modules\account\models\Ledger;
use app\modules\account\repositories\LedgerRepository;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Supplier;

class LedgerService
{
    private LedgerRepository $ledgerRepository;

    public function __construct()
    {
        $this->ledgerRepository = new LedgerRepository();
    }

    public static function batchInsert($invoice, array $ledgerArray): array
    {
        //dd($ledgerArray);
        foreach ($ledgerArray as $value) {
            $invoiceNumber = !$invoice ? '' : ' - ' . $invoice->invoiceNumber;
            $ledgerRequestData = [
                'title' => 'Service Purchase' . $invoiceNumber,
                'reference' => 'Invoice Number' . $invoiceNumber,
                'refId' => $value['refId'],
                'refModel' => $value['refModel'],
                'subRefId' => isset($value['subRefId']) ? $value['subRefId'] : (($invoice) ? $invoice->id : null),
                'subRefModel' => isset($value['subRefModel']) ? $value['subRefModel'] : (($invoice) ? Invoice::class : null),
                'debit' => $value['debit'],
                'credit' => $value['credit']
            ];
            $response = (new LedgerService)->store($ledgerRequestData);
            if ($response['error']) {
                return $response;
            }
        }

        return ['error' => false, 'message' => 'Ledger created successfully.'];
    }

    public function store(array $requestData): array
    {
        $balance = 0;
        if ($previousLedger = $this->ledgerRepository->findLatestOne($requestData['refId'], $requestData['refModel'])) {
            $balance = $previousLedger['balance'];
        }

        // Process Ledger data
        $newLedger = new Ledger();
        $newLedger->load(['Ledger' => $requestData]);
        $closingBalance = self::calculateBalance($requestData['refModel'], $newLedger, $balance);
        $newLedger->balance = $closingBalance;
        $newLedger->date = date('Y-m-d');
        $newLedger->status = GlobalConstant::ACTIVE_STATUS;
        $newLedger = $this->ledgerRepository->store($newLedger);
        if ($newLedger->hasErrors()) {
            return ['error' => true, 'message' => 'Ledger creation failed - ' . Utilities::processErrorMessages($newLedger->getErrors())];
        }

        return ['error' => false, 'message' => 'Ledger created successfully.'];
    }

    private static function calculateBalance($refModel, $ledger, $balance): float
    {
        $closingBalance = 0;
        switch ($refModel) {
            case Customer::class :
                $closingBalance = self::calculateCustomerClosingBalance($ledger, $balance);
                break;
            case Supplier::class :
                $closingBalance = self::calculateSupplierClosingBalance($ledger, $balance);
                break;
            case BankAccount::class :
                $closingBalance = self::calculateBankClosingBalance($ledger, $balance);
                break;
        }
        return $closingBalance;
    }

    private static function calculateCustomerClosingBalance(Ledger $ledger, float $balance): float
    {
        // balance => previous ledger balance
        // Customer Service purchase -  (credit < debit)
        // Payment from customer - (credit > debit)
        return ($ledger->debit > $ledger->credit) ? ($balance + ($ledger->credit + $ledger->debit)) : ($balance - ($ledger->credit + $ledger->debit));
    }

    private static function calculateSupplierClosingBalance(Ledger $ledger, float $balance): float
    {
        // balance => previous ledger balance
        // Purchase from Supplier -  (credit > debit)
        // Payment to Supplier - (credit < debit)
        return ($ledger->credit > $ledger->debit) ? ($balance + ($ledger->credit + $ledger->debit)) : ($balance - ($ledger->credit + $ledger->debit));
    }

    private static function calculateBankClosingBalance(Ledger $ledger, float $balance): float
    {
        // balance => previous ledger balance
        // Payment Received to Bank -  (debit > credit)
        // Payment Done from Bank - (credit > debit)
        return ($ledger->debit > $ledger->credit) ? ($balance + ($ledger->credit + $ledger->debit)) : ($balance - ($ledger->credit + $ledger->debit));
    }

    public function processSingleSupplierLedger($motherTicket, $ticketSupplier, $invoiceDetail): array
    {
        $debit = $credit = 0;
        $motherTicketSupplier = $motherTicket->ticketSupplier;
        if ($motherTicketSupplier->paymentStatus == GlobalConstant::PAYMENT_STATUS['Due']) {
            $debit = ($motherTicketSupplier->costOfSale - $ticketSupplier->costOfSale);
        } else {
            $amount = ($motherTicketSupplier->paidAmount - $ticketSupplier->costOfSale);
            if ($amount > 0) {
                $debit = $amount;
            } else {
                $credit = abs($amount);
            }
        }

        $ledgerRequestData = [
            'title' => 'Service Refund',
            'reference' => 'Service Refund',
            'refId' => $ticketSupplier->supplierId,
            'refModel' => Supplier::class,
            'subRefId' => $invoiceDetail->invoiceId,
            'subRefModel' => Invoice::class,
            'debit' => $debit,
            'credit' => $credit
        ];

        $balance = 0;
        if ($previousLedger = $this->ledgerRepository->findLatestOne($ledgerRequestData['refId'], $ledgerRequestData['refModel'])) {
            $balance = $previousLedger['balance'];
        }

        // Process Ledger data
        $newLedger = new Ledger();
        $newLedger->load(['Ledger' => $ledgerRequestData]);
        $closingBalance = self::calculateBalance($ledgerRequestData['refModel'], $newLedger, $balance);
        $newLedger->balance = $closingBalance;
        $newLedger->date = date('Y-m-d');
        $newLedger->status = GlobalConstant::ACTIVE_STATUS;

        $newLedger = $this->ledgerRepository->store($newLedger);
        if ($newLedger->hasErrors()) {
            return ['error' => true, 'message' => 'Ledger creation failed - ' . Utilities::processErrorMessages($newLedger->getErrors())];
        }
        return ['error' => false, 'message' => 'Supplier ledger processed successfully'];
    }

    public static function updateLedger($data): array
    {
        $currentLedger = Ledger::find()->where(['refId' => $data['refId'], 'refModel' => $data['refModel'], 'subRefId' => $data['subRefId'], 'subRefModel' => $data['subRefModel']])->one();
        // if ledger not found create new one
        if (!$currentLedger) {
            return ['error' => true, 'message' => 'Ledger not found.'];
        }

        $oldBalance = $currentLedger->balance;
        if (!$currentLedger->load(['Ledger' => $data])) {
            return ['error' => true, 'message' => 'Ledger data loading failed.'];
        }

        $balanceDifference = ((double)$currentLedger->balance - (double)$oldBalance);

        // Current Ledger update
        $currentLedger->debit = $data['debit'];
        $currentLedger->credit = $data['credit'];
        $currentLedger->balance += $balanceDifference;
        $currentLedger = (new LedgerRepository())->store($currentLedger);
        if (!$currentLedger->hasErrors()) {
            return ['error' => true, 'message' => 'Requested Ledger update failed ' . Utilities::processErrorMessages($currentLedger->errors)];
        }

        // Following ledger update
        $ledgersToUpdate = Ledger::find()->where(['refId' => $data['refId'], 'refModel' => $data['refModel']])->andWhere(['>', 'id', $currentLedger->id])->orderBy(['id' => SORT_ASC])->all();
        if (count($ledgersToUpdate)) {
            $balanceEquation = ($balanceDifference > 0) ? 'balance + ' . $balanceDifference : 'balance -' . $balanceDifference;
            $ledgerUpdateResponse = (new LedgerRepository())->update(['balance' => $balanceEquation], ['and', ['refId' => $data['refId']], ['refModel' => $data['refModel']], ['status' => GlobalConstant::ACTIVE_STATUS], ['>', 'id', $currentLedger->id]], Ledger::class);
            if (!$ledgerUpdateResponse) {
                return ['error' => true, 'message' => 'Following ledger update failed.'];
            }
        }

        return ['status' => true, 'message' => 'Ledger updated successfully.'];
    }
}