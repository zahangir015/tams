<?php

namespace app\modules\account\controllers;

use app\components\GlobalConstant;
use app\modules\account\models\Expense;
use app\modules\account\models\ExpenseCategory;
use app\modules\account\models\ExpenseSubCategory;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\visa\Visa;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

class AccountReportController extends Controller
{

    public function actionProfitLoss($dateRange = '')
    {
        if (!is_null($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);
            $date = date('jS \of F', strtotime($start_date)) . ' to ' . date('jS \of F', strtotime($end_date));
        } else {
            list($start_date, $end_date) = explode(' - ', date('Y-m-d') . ' - ' . date('Y-m-d'));
            $date = date('jS \of F');
        }
        $salesData = [];

        // Flight Data
        $flightData = Ticket::find()
            ->select([
                new Expression('COUNT(ticket.id) as total'),
                new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                new Expression('SUM(ticket.baseFare) as baseFare'),
                new Expression('SUM(ticket.tax) as tax'),
                new Expression('SUM(ticket.otherTax) as otherTax'),
                new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                new Expression('SUM(ticket.costOfSale) as costOfSale'),
                new Expression('SUM(ticket.netProfit) as netProfit'),
            ])
            ->where(['<=', Ticket::tableName() . '.refundRequestDate', $end_date])
            ->orWhere(['IS', Ticket::tableName() . '.refundRequestDate', NULL])
            ->andWhere(['between', Ticket::tableName() . '.issueDate', $start_date, $end_date])
            ->andWhere([Ticket::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
            ->andWhere([Ticket::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->orderBy('total DESC')
            ->asArray()->one();
        $salesData['Flight'] = [
            'qty' => $flightData['total'],
            'totalSegments' => $flightData['numberOfSegment'],
            'gross' => ($flightData['baseFare'] + $flightData['tax'] + $flightData['otherTax']),
            'totalQuote' => $flightData['quoteAmount'],
            'totalCost' => $flightData['costOfSale'],
            'totalReceived' => $flightData['receivedAmount'],
            'totalDue' => ($flightData['quoteAmount'] - $flightData['receivedAmount']),
            'totalNetProfit' => $flightData['netProfit'],
        ];

        // Holiday Data
        $holidayData = Holiday::find()
            ->select([
                new Expression('COUNT(holiday.id) as total'),
                new Expression('SUM(holiday.costOfSale) as costOfSale'),
                new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                new Expression('SUM(holiday.netProfit) as netProfit'),
            ])
            ->where(['<=', Holiday::tableName() . '.refundRequestDate', $end_date])
            ->orWhere(['IS', Holiday::tableName() . '.refundRequestDate', NULL])
            ->andWhere(['between', Holiday::tableName() . '.issueDate', $start_date, $end_date])
            ->andWhere([Holiday::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
            ->andWhere([Holiday::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->orderBy('total DESC')
            ->asArray()->one();

        $salesData['Holiday'] = [
            'qty' => $holidayData['total'],
            'totalQuote' => $holidayData['quoteAmount'],
            'totalReceived' => $holidayData['receivedAmount'],
            'totalCost' => $holidayData['costOfSale'],
            'totalDue' => ($holidayData['quoteAmount'] - $holidayData['receivedAmount']),
            'totalNetProfit' => $holidayData['netProfit'],
        ];
        // Hotel Data
        $hotelData = Hotel::find()
            ->select([
                new Expression('COUNT(hotel.id) as total'),
                new Expression('SUM(hotel.costOfSale) as costOfSale'),
                new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                new Expression('SUM(hotel.netProfit) as netProfit'),
            ])
            ->where(['<=', Hotel::tableName() . '.refundRequestDate', $end_date])
            ->orWhere(['IS', Hotel::tableName() . '.refundRequestDate', NULL])
            ->andWhere(['between', Hotel::tableName() . '.issueDate', $start_date, $end_date])
            ->andWhere([Hotel::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
            ->andWhere([Hotel::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->orderBy('total DESC')
            ->asArray()->one();

        $salesData['Hotel'] = [
            'qty' => $hotelData['total'],
            'totalQuote' => $hotelData['quoteAmount'],
            'totalReceived' => $hotelData['receivedAmount'],
            'totalCost' => $holidayData['costOfSale'],
            'totalDue' => ($hotelData['quoteAmount'] - $hotelData['receivedAmount']),
            'totalNetProfit' => $hotelData['netProfit'],
        ];
        // Visa Data
        $visaData = Visa::find()
            ->select([
                new Expression('COUNT(visa.id) as total'),
                new Expression('SUM(visa.costOfSale) as costOfSale'),
                new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                new Expression('SUM(visa.netProfit) as netProfit'),
            ])
            ->where(['<=', Visa::tableName() . '.refundRequestDate', $end_date])
            ->orWhere(['IS', Visa::tableName() . '.refundRequestDate', NULL])
            ->andWhere(['between', Visa::tableName() . '.issueDate', $start_date, $end_date])
            ->andWhere([Visa::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
            ->andWhere([Visa::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->orderBy('total DESC')
            ->asArray()->one();

        $salesData['Visa'] = [
            'qty' => $visaData['total'],
            'totalQuote' => $visaData['quoteAmount'],
            'totalReceived' => $visaData['receivedAmount'],
            'totalCost' => $holidayData['costOfSale'],
            'totalDue' => ($visaData['quoteAmount'] - $visaData['receivedAmount']),
            'totalNetProfit' => $visaData['netProfit'],
        ];

        $expenseData = [];

        $expenses = Expense::find()
            ->with(['category', 'subCategory'])
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalCost) as totalCost'),
                new Expression('SUM(totalPaid) as totalPaid'),
                new Expression('SUM(totalCost - totalPaid) as dueAmount'),
                'categoryId',
                'subCategoryId'])
            ->where(['between', 'accruingMonth', $start_date, $end_date])
            ->groupBy(['categoryId', 'subCategoryId'])
            ->orderBy('total DESC')
            ->all();

        if ($expenses) {
            $expenseSum = 0;
            $categoryExpenseSum = [];

            foreach ($expenses as $key => $expense) {
                $expenseSum += $expense->totalCost;
                $expenseData[$expense->category->name][] = $expense;
                if (isset($categoryExpenseSum[$expense->category->name])){
                    $categoryExpenseSum[$expense->category->name]['sum'] += $expense->totalCost;
                }else{
                    $categoryExpenseSum[$expense->category->name]['sum'] = $expense->totalCost;
                }
            }
        }

        return $this->render('profit_loss', [
            'data' => $salesData,
            'expenseData' => $expenseData,
            'expenseSum' => $expenseSum,
            'categoryExpenseSum' => $categoryExpenseSum
        ]);
    }
}