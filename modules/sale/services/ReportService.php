<?php

namespace app\modules\sale\services;

use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\ticket\Ticket;
use yii\db\Expression;

class ReportService
{
    public static function monthlySalesReport($startDate, $endDate){
        $ticketData[date('Y-m', strtotime($startDate))] = Ticket::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(numberOfSegment) as numberOfSegment'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(baseFare) as baseFare'),
                new Expression('SUM(tax) as tax'),
                new Expression('SUM(otherTax) as otherTax'),
                new Expression('SUM(serviceCharge) as serviceCharge'),
                new Expression('SUM(discount) as discount'),
                new Expression('SUM(markupAmount) as markupAmount'),
                new Expression('SUM(convenienceFee) as convenienceFee'),
                new Expression('SUM(ait) as ait'),
                new Expression('SUM(commissionReceived) as commissionReceived'),
                new Expression('SUM(incentiveReceived) as incentiveReceived'),
                'type'
            ])
            ->where(['<=', 'refundRequestDate', $endDate])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $startDate, $endDate])
            ->andWhere(['<>', 'type', ServiceConstant::ALL_TICKET_TYPE['Refund']])
            ->groupBy('type')
            ->orderBy('total DESC')->asArray()->all();

        $ticketRefundData[date('Y-m', strtotime($startDate))] = Ticket::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(numberOfSegment) as numberOfSegment'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(baseFare) as baseFare'),
                new Expression('SUM(tax) as tax'),
                new Expression('SUM(otherTax) as otherTax'),
                new Expression('SUM(serviceCharge) as serviceCharge'),
                new Expression('SUM(discount) as discount'),
                new Expression('SUM(markupAmount) as markupAmount'),
                new Expression('SUM(convenienceFee) as convenienceFee'),
                new Expression('SUM(ait) as ait'),
                new Expression('SUM(commissionReceived) as commissionReceived'),
                new Expression('SUM(incentiveReceived) as incentiveReceived'),
                'type'
            ])
            ->where(['between', 'refundRequestDate', $startDate, $endDate])
            ->andWhere(['type' => ServiceConstant::ALL_TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();
    }
    public static function quoteAmountCalculationForReport($key = null, $value, $refundData): float
    {
        $refundQuoteAmount = 0;
        if (isset($refundData[$key]['quoteAmount'])) {
            $refundQuoteAmount = (double)$refundData[$key]['quoteAmount'];
        }
        return (double)array_sum(array_column($value, 'quoteAmount')) + $refundQuoteAmount;
    }
}