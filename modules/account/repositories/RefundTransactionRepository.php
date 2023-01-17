<?php

namespace app\modules\account\repositories;

use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\repository\ParentRepository;

class RefundTransactionRepository extends ParentRepository
{
    public function customerPendingRefundServices($customerId, $start_date, $end_date): array|null
    {
        return Customer::find()
            ->select(['id', 'name', 'company'])
            ->with([
                'tickets' => function ($query) use ($start_date, $end_date, $customerId) {
                    $query->joinWith(['ticketRefund' => function ($subQuery) use ($customerId) {
                        $subQuery->where(['!=', 'isRefunded', ServiceConstant::STATE['Full Refund']])
                            ->andWhere(['refId' => $customerId])
                            ->andWhere(['refModel' => Customer::class]);
                    }])
                        ->where(['type' => ServiceConstant::TYPE['Refund']]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'ticket.refundRequestDate', $start_date, $end_date])
                            ->orderBy(['ticket.refundRequestDate' => SORT_ASC]);
                    }
                },
                'visas' => function ($query) use ($start_date, $end_date, $customerId) {
                    $query->joinWith(['visaRefund' => function ($subQuery) use ($customerId) {
                        $subQuery->where(['!=', 'isRefunded', ServiceConstant::STATE['Full Refund']])
                            ->andWhere(['refId' => $customerId])
                            ->andWhere(['refModel' => Customer::class]);
                    }])
                        ->where(['type' => ServiceConstant::TYPE['Refund']]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'visa.refundRequestDate', $start_date, $end_date])
                            ->orderBy(['visa.refundRequestDate' => SORT_ASC]);
                    }
                },
                'hotels' => function ($query) use ($start_date, $end_date, $customerId) {
                    $query->joinWith(['hotelRefund' => function ($subQuery) use ($customerId) {
                        $subQuery->where(['!=', 'isRefunded', ServiceConstant::STATE['Full Refund']])
                            ->andWhere(['refId' => $customerId])
                            ->andWhere(['refModel' => Customer::class]);
                    }])
                        ->where(['type' => ServiceConstant::TYPE['Refund']]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'hotel.refundRequestDate', $start_date, $end_date])
                            ->orderBy(['hotel.refundRequestDate' => SORT_ASC]);
                    }
                },
                'holidays' => function ($query) use ($start_date, $end_date, $customerId) {
                    $query->joinWith(['holidayRefund' => function ($subQuery) use ($customerId) {
                        $subQuery->where(['!=', 'isRefunded', ServiceConstant::STATE['Full Refund']])
                            ->andWhere(['refId' => $customerId])
                            ->andWhere(['refModel' => Customer::class]);
                    }])
                        ->where(['type' => ServiceConstant::TYPE['Refund']]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'holiday.refundRequestDate', $start_date, $end_date])
                            ->orderBy(['holiday.refundRequestDate' => SORT_ASC]);
                    }
                },
            ])
            ->where(['id' => $customerId])->asArray()->one();
    }
}