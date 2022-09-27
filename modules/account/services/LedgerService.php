<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Invoice;
use app\modules\account\models\Ledger;
use app\modules\account\repositories\LedgerRepository;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Supplier;

class LedgerService
{
    public static function batchInsert(Invoice $invoice, array $ledgerArray): array
    {
        foreach ($ledgerArray as $key => $value) {
            $ledgerRequestData = [
                'title' => 'Service Purchase - '.$invoice->invoiceNumber,
                'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                'refId' => $value['refId'],
                'refModel' => $value['refModel'],
                'subRefId' => ($value['subRefId']) ?? $invoice->id,
                'subRefModel' => ($value['subRefModel']) ?? Invoice::class,
                'debit' => $value['debit'],
                'credit' => $value['credit']
            ];
            $response = self::store($ledgerRequestData);
            if ($response['error']) {
                return $response;
            }
        }

        return ['error' => false, 'message' => 'Ledger created successfully.'];
    }

    public static function store(array $requestData): array
    {
        $balance = 0;
        if ($previousLedger = LedgerRepository::findLatestOne($requestData['refId'], $requestData['refModel'])) {
            $balance = $previousLedger['balance'];
        }

        // Process Ledger data
        $newLedger = new Ledger();
        $newLedger->load(['Ledger' => $requestData]);
        $closingBalance = self::calculateBalance($requestData['refModel'], $newLedger, $balance);
        $newLedger->balance = $closingBalance;
        $newLedger->date = date('Y-m-d');
        $newLedger->status = GlobalConstant::ACTIVE_STATUS;
        $newLedger = LedgerRepository::store($newLedger);
        if ($newLedger->hasErrors()) {
            return ['error' => true, 'message' => 'Ledger creation failed - ' . Helper::processErrorMessages($newLedger->getErrors())];
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

    public static function processSingleSupplierLedger($motherTicket, $ticketSupplier, $invoiceDetail): array
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
        dd($ledgerRequestData);
        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => $ledgerRequestResponse['message']];
        }
        return ['error' => false, 'message' => 'Supplier ledger processed successfully'];
    }
}