<?php

namespace app\modules\account\services;

use app\modules\account\models\RefundTransaction;
use yii\helpers\ArrayHelper;

class RefundTransactionService
{
    public function getRefundList($refModel, $refId): array
    {
        $refundTransactions =  RefundTransaction::find()
            ->select([
                'refund_transaction.id',
                'refund_transaction.refId',
                'refund_transaction.refModel',
                'concat(identificationNumber," | ",totalAmount) as name',
                'refund_transaction.identificationNumber',
                'refund_transaction.totalAmount',
                /*'refund_transaction.payableAmount',
                'refund_transaction.receivableAmount',
                'refund_transaction.adjustedAmount'*/
            ])
            ->where(['like', 'refund_transaction.refModel', $refModel])
            ->andWhere(['refund_transaction.refId' => $refId])
            ->all();

        return ArrayHelper::map($refundTransactions, 'id', 'name');
    }
}