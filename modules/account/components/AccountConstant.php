<?php

namespace app\modules\account\components;

use app\modules\account\models\BankAccount;
use app\modules\account\models\Bill;
use app\modules\account\models\Expense;
use app\modules\account\models\Invoice;
use app\modules\account\models\Journal;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Supplier;

final class AccountConstant
{
    const REFUND_TRANSACTION_DUE = 0;
    const REFUND_TRANSACTION_PARTIALLY_PAID = 1;
    const REFUND_TRANSACTION_FULL_PAID = 2;

    const REPORT_TYPE = [
        'Balance Sheet' => 'Balance Sheet',
        'Income Statement' => 'Income Statement',
        'Retained Earnings' => 'Retained Earnings',
    ];

    const PAYMENT_MODE = [
        'Cheque' => 'Cheque',
        'Cash' => 'Cash',
        'POS' => 'POS',
        'Online Payment' => 'Online Payment',
        'Credit/Debit Card' => 'Credit/Debit Card',
        'Adjustment' => 'Adjustment',
        'Advance Adjustment' => 'Advance Adjustment',
        'Coupon' => 'Coupon',
    ];

    const REF_MODEL = [
        Customer::class => 'Customer',
        Supplier::class => 'Supplier',
        BankAccount::class => 'Bank',
    ];

    const WITHOUT_BANK_REF_MODEL = [
        Customer::class => 'Customer',
        Supplier::class => 'Supplier',
    ];

    const SUB_REF_MODEL = [
        Invoice::class => 'Invoice',
        Bill::class => 'Bill',
        Expense::class => 'Expense',
        Journal::class => 'Journal',
    ];

    const REFUND_PAYMENT_TYPE = [
        'Payable' => 'Payable',
        'Receivable' => 'Receivable'
    ];

    const REFUND_TRANSACTION_PAYMENT_STATUS = [
        'Due', 'Partially Refunded', 'Fully Refunded'
    ];
}