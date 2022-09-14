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
                'title' => 'Service Purchase',
                'reference' => 'Service Purchase',
                'refId' => $key,
                'refModel' => Supplier::class,
                'subRefId' => ($value['subRefId']) ?? $invoice->id,
                'subRefModel' => Invoice::class,
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
}