<?php

namespace app\modules\account\repositories;

use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\repository\ParentRepository;

class RefundTransactionRepository extends ParentRepository
{
    public function customerPendingRefundServices($customerId, $start_date, $end_date): \yii\db\ActiveRecord|null
    {
        return Customer::find()
            ->select(['id', 'name', 'company'])
            ->with([
                'tickets' => function ($query) use ($start_date, $end_date, $customerId) {
                    $query->joinWith(['ticketRefund' => function ($subQuery) use ($customerId) {
                        $subQuery->where(['!=', 'isRefunded', ServiceConstant::STATE['Full Refund']])
                            ->andWhere(['refId' => $customerId])
                            ->andWhere(['refModel' => Customer::class]);
                    }, 'ON ticket.id = ticket_refund.ticketId'])
                        ->where(['type' => ServiceConstant::TYPE['Refund']]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'RefundRequestDate', $start_date, $end_date])
                            ->orderBy(['RefundRequestDate' => SORT_ASC]);
                    }
                },
                'visas' => function ($query) use ($start_date, $end_date, $customerId) {
                    $query->joinWith(['visaRefund' => function ($subQuery) use ($customerId) {
                        $subQuery->where(['!=', 'isRefunded', ServiceConstant::STATE['Full Refund']])
                            ->andWhere(['refId' => $customerId])
                            ->andWhere(['refModel' => Customer::class]);
                    }, 'ON visa.id = visa_refund.visaId'])
                        ->where(['type' => ServiceConstant::TYPE['Refund']]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'RefundRequestDate', $start_date, $end_date])
                            ->orderBy(['RefundRequestDate' => SORT_ASC]);
                    }
                },
                'hotels' => function ($query) use ($start_date, $end_date, $customerId) {
                    $query->joinWith(['hotelRefund' => function ($subQuery) use ($customerId) {
                        $subQuery->where(['!=', 'isRefunded', ServiceConstant::STATE['Full Refund']])
                            ->andWhere(['refId' => $customerId])
                            ->andWhere(['refModel' => Customer::class]);
                    }, 'ON hotel.id = hotel_refund.hotelId'])
                        ->where(['type' => ServiceConstant::TYPE['Refund']]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'RefundRequestDate', $start_date, $end_date])
                            ->orderBy(['RefundRequestDate' => SORT_ASC]);
                    }
                },
                'holidays' => function ($query) use ($start_date, $end_date, $customerId) {
                    $query->joinWith(['holidayRefund' => function ($subQuery) use ($customerId) {
                        $subQuery->where(['!=', 'isRefunded', ServiceConstant::STATE['Full Refund']])
                            ->andWhere(['refId' => $customerId])
                            ->andWhere(['refModel' => Customer::class]);
                    }, 'ON holiday.id = holiday_refund.holidayId'])
                        ->where(['type' => ServiceConstant::TYPE['Refund']]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'RefundRequestDate', $start_date, $end_date])
                            ->orderBy(['RefundRequestDate' => SORT_ASC]);
                    }
                },
            ])
            ->where(['id' => $customerId])->one();
    }
}