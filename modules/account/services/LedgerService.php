<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Ledger;
use app\modules\account\repositories\LedgerRepository;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Supplier;

class LedgerService
{
    public static function storeLedger(array $requestData): array
    {
        $balance = 0;
        $oldLedger = LedgerRepository::findLatestOne($requestData['refId'], $requestData['refModel']); // bank/customer/supplier
        if ($oldLedger) {
            $balance = $oldLedger['balance'];
        }

        // Process Ledger data
        $Ledger = new Ledger();
        $Ledger->load(['Ledger' => $requestData]);
        $closingBalance = self::calculateBalance($requestData['refModel'], $Ledger, $balance);
        $Ledger->balance = $closingBalance;
        $Ledger->date = date('Y-m-d');
        $Ledger->status = GlobalConstant::ACTIVE_STATUS;
        $Ledger = LedgerRepository::store($Ledger);
        if ($Ledger->hasErrors()) {
            return ['error' => true, 'message' => 'Ledger creation failed - '.Helper::processErrorMessages($Ledger->getErrors())];
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