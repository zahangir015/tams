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
            'totalReceived' => $flightData['receivedAmount'],
            'totalDue' => ($flightData['quoteAmount'] - $flightData['receivedAmount']),
            'netProfit' => $flightData['netProfit'],
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
            'totalDue' => ($holidayData['quoteAmount'] - $holidayData['receivedAmount']),
            'netProfit' => $holidayData['netProfit'],
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
            'totalDue' => ($hotelData['quoteAmount'] - $hotelData['receivedAmount']),
            'netProfit' => $hotelData['netProfit'],
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
            'totalDue' => ($visaData['quoteAmount'] - $visaData['receivedAmount']),
            'netProfit' => $visaData['netProfit'],
        ];

        $expenseData = [];
        $expenseSum = [];

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
            foreach ($expenses as $key => $expense) {
                $expenseSum +=  $expense->totalCost;

                $expenseData[$expense->category->name]['sum'] = $expense;
                    $expenseData[$expense->category->name][$expense->subCategory->name][date('Y-m')] = $subCategory;
            }
        }

        return $this->render('pl', [
            'data' => $monthWiseData,
            'expenseData' => $expenseData,
            'expenseSum' => $expenseSum
        ]);
    }

    public function actionBalanceSheet($dateRange = '')
    {
        $monthWiseData = [];
        $expenseData = [];
        if (!is_null($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);

            $date1 = DateTime::createFromFormat('Y-m-d', $start_date);
            $date2 = DateTime::createFromFormat('Y-m-d', $end_date);
            $diff = $date1->diff($date2)->m;

            if ($diff >= 1) {
                $start = (new DateTime($start_date))->modify('first day of this month');
                $end = (new DateTime($end_date))->modify('first day of next month');
                $interval = DateInterval::createFromDateString('1 month');
                $period = new DatePeriod($start, $interval, $end);

                foreach ($period as $dt) {

                    $monthWiseData[$dt->format("Y-m")]['cash'] = MonthlyBankClosing::find()
                        ->select([new Expression('SUM(amount) as amount'),])
                        ->where(['between', 'month', $dt->format("Y-m-d"), $dt->format("Y-m-t")])->one();

                    $monthWiseData[$dt->format("Y-m")]['figure'] = ArrayHelper::map(MonthlyFigure::find()
                        ->select([new Expression('SUM(amount) as amount'), 'title'])
                        ->where(['between', 'month', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->groupBy(['title'])->all(), 'title', 'amount');

                    $monthWiseData[$dt->format("Y-m")]['cash'] = MonthlyBankClosing::find()
                        ->select([new Expression('SUM(amount) as amount'),])
                        ->where(['between', 'month', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->one();

                    $monthWiseData[$dt->format("Y-m")]['ticket'] = Tickets::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(payToAgent) as payToAgent'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])->one();

                    $monthWiseData[$dt->format("Y-m")]['Package'] = Packages::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->one();
                    $monthWiseData[$dt->format("Y-m")]['Package']->totalPayable = PackageSuppliers::find()
                        ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                        ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

                    $monthWiseData[$dt->format("Y-m")]['visa'] = Visas::find()
                        ->select([
                            new Expression('SUM(totalQty) as total'),
                            new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->one();
                    $monthWiseData[$dt->format("Y-m")]['visa']->totalPayable = VisaSuppliers::find()
                        ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                        ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])->all(), 'id', 'id')])->one()->totalPayable;

                    $fixedAssetsCatIds = ArrayHelper::map(FixedAsset::find()
                        ->select(['catId'])
                        ->where(['<', 'date(inServiceDate)', $dt->format("Y-m-t")])
                        ->andWhere(['isDisposed' => 0])
                        ->groupBy(['catId'])
                        ->all(), 'catId', 'catId');
                    $assets = FixedAsset::find()
                        ->select(['id', 'title', 'cost', 'paidAmount', 'dueAmount', 'expectedLife', 'inServiceDate'])
                        ->where(['<', 'date(inServiceDate)', $dt->format("Y-m-t")])
                        ->andWhere(['catId' => $fixedAssetsCatIds])
                        ->andWhere(['isDisposed' => 0])
                        ->all();
                    if (!is_null($assets)) {
                        $totalCost = $totalDep = $totalNBV = 0;
                        foreach ($assets as $item) {
                            $numberOfDays = $datediff = 0;
                            $totalCost = $totalCost + $item->cost;
                            if (strtotime($item->inServiceDate) < strtotime($start_date)) {
                                $datediff = strtotime($dt->format("Y-m-t")) - strtotime($start_date);
                                $numberOfDays = round($datediff / (60 * 60 * 24)) + 1;
                            } else {
                                $datediff = strtotime($dt->format("Y-m-t")) - strtotime($item->inServiceDate);
                                $numberOfDays = round($datediff / (60 * 60 * 24)) + 1;
                            }
                            $fig = ($item->expectedLife) ? (($item->cost / $item->expectedLife) * $numberOfDays) : 0;
                            $totalDep = $totalDep + round($fig);
                            $totalNBV = $totalNBV + round($item->cost - $fig);
                        }
                    }
                    $monthWiseData[$dt->format("Y-m")]['asset'] = $totalNBV;

                    //PL calculation
                    $plTicketData = Tickets::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(segment) as segment'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(payToAgent) as payToAgent'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                            new Expression('SUM(baseFare) as baseFare'),
                            new Expression('SUM(tax) as tax'),
                            new Expression('SUM(otherTax) as otherTax'),
                            new Expression('SUM(serviceCharge) as serviceCharge'),
                            new Expression('SUM(commissionReceived) as commissionReceived'),
                            new Expression('SUM(incentiveReceived) as incentiveReceived'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['<', 'issueDate', $dt->format("Y-m-t")])
                        ->andWhere(['<>', 'type', 'Refund'])->one();

                    $plTicketDataRefundData = Tickets::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(segment) as segment'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(payToAgent) as payToAgent'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['<', 'issueDate', $dt->format("Y-m-t")])
                        ->andWhere(['type' => 'Refund'])
                        ->orderBy('total DESC')->one();
                    $plPackageData = Packages::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['<', 'issueDate', $dt->format("Y-m-t")])
                        ->one();
                    $plPackageData->totalPayable = PackageSuppliers::find()
                        ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                        ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['<', 'issueDate', $dt->format("Y-m-t")])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

                    $plVisaData = Visas::find()
                        ->select([
                            new Expression('SUM(totalQty) as total'),
                            new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['<', 'issueDate', $dt->format("Y-m-t")])
                        ->one();
                    $plVisaData->totalPayable = VisaSuppliers::find()
                        ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                        ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['<', 'issueDate', $dt->format("Y-m-t")])->all(), 'id', 'id')])->one()->totalPayable;

                    $plExpensesData = Expense::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(amount) as amount'),
                            new Expression('SUM(paidAmount) as paidAmount'),
                            new Expression('SUM(dueAmount) as dueAmount'), 'catId', 'dateOfTransaction'])
                        ->where(['<', 'date(dateOfTransaction)', $dt->format("Y-m-t")])
                        ->one();

                    $ait = ((double)$plTicketData->baseFare + (double)$plTicketData->tax + (double)$plTicketData->otherTax) * 0.003;
                    $gross = ((double)$plTicketData->baseFare + (double)$plTicketData->tax + (double)$plTicketData->otherTax + $ait);
                    $quoteAmount = (double)$plTicketData->quoteAmount;
                    $discount = ($gross - $quoteAmount);

                    $commission = (double)$plTicketData->commissionReceived;
                    $incentive = (double)$plTicketData->incentiveReceived;

                    //$airTicket = ((double)$plTicketData->quoteAmount + (double)$plTicketDataRefundData->quoteAmount) + $discount;
                    $airTicket = (($commission + $incentive - $ait));
                    $visa = (double)$plVisaData->quoteAmount - (double)$plVisaData->totalCostOfSale;
                    $package = (double)$plPackageData->quoteAmount - (double)$plPackageData->totalCostOfSale;
                    $totalIncome = ($airTicket + $visa + $package);

                    // expense calculation
                    $tax = (double)$plTicketData->tax;
                    $otherTax = (double)$plTicketData->otherTax;
                    $baseFare = (double)$plTicketData->baseFare;
                    $serviceCharge = ((double)$plTicketData->serviceCharge + (double)$plTicketDataRefundData->serviceCharge);
                    $visaExp = (double)$plVisaData->totalCostOfSale;
                    $packageExp = (double)$plPackageData->totalCostOfSale;
                    $totalExp = ($tax + $otherTax + $baseFare + $ait + $serviceCharge + $visaExp + $packageExp + $discount);

                    $monthWiseData[$dt->format("Y-m")]['income'] = $totalIncome;
                    $monthWiseData[$dt->format("Y-m")]['serviceExpense'] = $totalExp;
                    $monthWiseData[$dt->format("Y-m")]['otherExpense'] = $plExpensesData->amount;
                }
            } else {

                $monthWiseData[date('Y-m', strtotime($start_date))]['cash'] = MonthlyBankClosing::find()
                    ->select([new Expression('SUM(amount) as amount'),])
                    ->where(['between', 'month', $start_date, $end_date])->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['figure'] = ArrayHelper::map(MonthlyFigure::find()
                    ->select([new Expression('SUM(amount) as amount'), 'title'])
                    ->where(['between', 'month', $start_date, $end_date])
                    ->groupBy(['title'])->all(), 'title', 'amount');

                $monthWiseData[date('Y-m', strtotime($start_date))]['cash'] = MonthlyBankClosing::find()
                    ->select([new Expression('SUM(amount) as amount'),])
                    ->where(['between', 'month', $start_date, $end_date])
                    ->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['ticket'] = Tickets::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(segment) as segment'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(payToAgent) as payToAgent'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', $start_date, $end_date])->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['package'] = Packages::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', $start_date, $end_date])
                    ->one();
                $monthWiseData[date('Y-m', strtotime($start_date))]['package']->totalPayable = PackageSuppliers::find()
                    ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                    ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['between', 'issueDate', $start_date, $end_date])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

                $monthWiseData[date('Y-m', strtotime($start_date))]['visa'] = Visas::find()
                    ->select([
                        new Expression('SUM(totalQty) as total'),
                        new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', $start_date, $end_date])
                    ->one();
                $monthWiseData[date('Y-m', strtotime($start_date))]['visa']->totalPayable = VisaSuppliers::find()
                    ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                    ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['between', 'issueDate', $start_date, $end_date])->all(), 'id', 'id')])->one()->totalPayable;

                $fixedAssetsCatIds = ArrayHelper::map(FixedAsset::find()
                    ->select(['catId'])
                    ->where(['<', 'date(inServiceDate)', $end_date])
                    ->andWhere(['isDisposed' => 0])
                    ->groupBy(['catId'])
                    ->all(), 'catId', 'catId');
                $assets = FixedAsset::find()
                    ->select(['id', 'title', 'cost', 'paidAmount', 'dueAmount', 'expectedLife', 'inServiceDate'])
                    ->where(['<', 'date(inServiceDate)', $end_date])
                    ->andWhere(['catId' => $fixedAssetsCatIds])
                    ->andWhere(['isDisposed' => 0])
                    ->all();
                if (!is_null($assets)) {
                    $totalCost = $totalDep = $totalNBV = 0;
                    foreach ($assets as $item) {
                        $numberOfDays = $datediff = 0;
                        $totalCost = $totalCost + $item->cost;
                        if (strtotime($item->inServiceDate) < strtotime($start_date)) {
                            $datediff = strtotime($end_date) - strtotime($start_date);
                            $numberOfDays = round($datediff / (60 * 60 * 24)) + 1;
                        } else {
                            $datediff = strtotime($end_date) - strtotime($item->inServiceDate);
                            $numberOfDays = round($datediff / (60 * 60 * 24)) + 1;
                        }
                        $fig = ($item->expectedLife) ? (($item->cost / $item->expectedLife) * $numberOfDays) : 0;
                        $totalDep = $totalDep + round($fig);
                        $totalNBV = $totalNBV + round($item->cost - $fig);
                    }
                }

                $monthWiseData[date('Y-m', strtotime($start_date))]['asset'] = $totalNBV;

                //PL calculation
                $plTicketData = Tickets::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(segment) as segment'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(payToAgent) as payToAgent'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                        new Expression('SUM(baseFare) as baseFare'),
                        new Expression('SUM(tax) as tax'),
                        new Expression('SUM(otherTax) as otherTax'),
                        new Expression('SUM(serviceCharge) as serviceCharge'),
                        new Expression('SUM(commissionReceived) as commissionReceived'),
                        new Expression('SUM(incentiveReceived) as incentiveReceived'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['<', 'issueDate', $end_date])
                    ->andWhere(['<>', 'type', 'Refund'])->one();

                $plTicketDataRefundData = Tickets::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(segment) as segment'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(payToAgent) as payToAgent'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['<', 'issueDate', $end_date])
                    ->andWhere(['type' => 'Refund'])
                    ->orderBy('total DESC')->one();
                $plPackageData = Packages::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['<', 'issueDate', $end_date])
                    ->one();
                $plPackageData->totalPayable = PackageSuppliers::find()
                    ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                    ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['<', 'issueDate', $end_date])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

                $plVisaData = Visas::find()
                    ->select([
                        new Expression('SUM(totalQty) as total'),
                        new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['<', 'issueDate', $end_date])
                    ->one();
                $plVisaData->totalPayable = VisaSuppliers::find()
                    ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                    ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['<', 'issueDate', $end_date])->all(), 'id', 'id')])->one()->totalPayable;

                $plExpensesData = Expense::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(amount) as amount'),
                        new Expression('SUM(paidAmount) as paidAmount'),
                        new Expression('SUM(dueAmount) as dueAmount'), 'catId', 'dateOfTransaction'])
                    ->where(['<', 'date(dateOfTransaction)', $end_date])
                    ->one();

                $ait = ((double)$plTicketData->baseFare + (double)$plTicketData->tax + (double)$plTicketData->otherTax) * 0.003;
                $gross = ((double)$plTicketData->baseFare + (double)$plTicketData->tax + (double)$plTicketData->otherTax + $ait);
                $quoteAmount = (double)$plTicketData->quoteAmount;
                $discount = ($gross - $quoteAmount);

                $commission = (double)$plTicketData->commissionReceived;
                $incentive = (double)$plTicketData->incentiveReceived;

                //$airTicket = ((double)$plTicketData->quoteAmount + (double)$plTicketDataRefundData->quoteAmount) + $discount;
                $airTicket = (($commission + $incentive - $ait));
                $visa = (double)$plVisaData->quoteAmount - (double)$plVisaData->totalCostOfSale;
                $package = (double)$plPackageData->quoteAmount - (double)$plPackageData->totalCostOfSale;
                $totalIncome = ($airTicket + $visa + $package);

                // expense calculation
                $tax = (double)$plTicketData->tax;
                $otherTax = (double)$plTicketData->otherTax;
                $baseFare = (double)$plTicketData->baseFare;
                $serviceCharge = ((double)$plTicketData->serviceCharge + (double)$plTicketDataRefundData->serviceCharge);
                $visaExp = (double)$plVisaData->totalCostOfSale;
                $packageExp = (double)$plPackageData->totalCostOfSale;
                $totalExp = ($tax + $otherTax + $baseFare + $ait + $serviceCharge + $visaExp + $packageExp + $discount);

                $totalIncome = ($airTicket + $visa + $package + $commission + $incentive);
                $monthWiseData[date('Y-m', strtotime($start_date))]['income'] = $totalIncome;
                $monthWiseData[date('Y-m', strtotime($start_date))]['serviceExpense'] = $totalExp;
                $monthWiseData[date('Y-m', strtotime($start_date))]['otherExpense'] = $plExpensesData->amount;
            }

        } else {
            $monthWiseData[date('Y-m')]['cash'] = MonthlyBankClosing::find()
                ->select([new Expression('SUM(amount) as amount'),])
                ->where(['month' => date('Y-m-d')])->one();

            $monthWiseData[date('Y-m')]['figure'] = ArrayHelper::map(MonthlyFigure::find()
                ->select([new Expression('SUM(amount) as amount'), 'title'])
                ->where(['month' => date('Y-m-d')])
                ->groupBy(['title'])->all(), 'title', 'amount');

            $monthWiseData[date('Y-m')]['cash'] = MonthlyBankClosing::find()
                ->select([new Expression('SUM(amount) as amount'),])
                ->where(['month' => date('Y-m-d')])
                ->one();

            $monthWiseData[date('Y-m')]['ticket'] = Tickets::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(segment) as segment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(payToAgent) as payToAgent'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['issueDate' => date('Y-m-d')])->one();

            $monthWiseData[date('Y-m')]['package'] = Packages::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['issueDate' => date('Y-m-d')])
                ->one();
            $monthWiseData[date('Y-m')]['package']->totalPayable = PackageSuppliers::find()
                ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['issueDate' => date('Y-m-d')])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

            $monthWiseData[date('Y-m')]['visa'] = Visas::find()
                ->select([
                    new Expression('SUM(totalQty) as total'),
                    new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['issueDate' => date('Y-m-d')])
                ->one();
            $monthWiseData[date('Y-m')]['visa']->totalPayable = VisaSuppliers::find()
                ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['issueDate' => date('Y-m-d')])->all(), 'id', 'id')])->one()->totalPayable;

            $fixedAssetsCatIds = ArrayHelper::map(FixedAsset::find()
                ->select(['catId'])
                ->where(['<', 'date(inServiceDate)', date('Y-m-d')])
                ->andWhere(['isDisposed' => 0])
                ->groupBy(['catId'])
                ->all(), 'catId', 'catId');
            $assets = FixedAsset::find()
                ->select(['id', 'title', 'cost', 'paidAmount', 'dueAmount', 'expectedLife', 'inServiceDate'])
                ->where(['<', 'date(inServiceDate)', date('Y-m-d')])
                ->andWhere(['catId' => $fixedAssetsCatIds])
                ->andWhere(['isDisposed' => 0])
                ->all();
            if (!is_null($assets)) {
                $totalCost = $totalDep = $totalNBV = 0;
                foreach ($assets as $item) {
                    $numberOfDays = $datediff = 0;
                    $totalCost = $totalCost + $item->cost;
                    if (strtotime($item->inServiceDate) < strtotime(date('Y-m-d'))) {
                        $datediff = strtotime(date('Y-m-d')) - strtotime(date('Y-m-d'));
                        $numberOfDays = round($datediff / (60 * 60 * 24)) + 1;
                    } else {
                        $datediff = strtotime(date('Y-m-d')) - strtotime($item->inServiceDate);
                        $numberOfDays = round($datediff / (60 * 60 * 24)) + 1;
                    }
                    $fig = ($item->expectedLife) ? (($item->cost / $item->expectedLife) * $numberOfDays) : 0;
                    $totalDep = $totalDep + round($fig);
                    $totalNBV = $totalNBV + round($item->cost - $fig);
                }
            }

            $monthWiseData[date('Y-m')]['asset'] = $totalNBV;

            //PL calculation
            $plTicketData = Tickets::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(segment) as segment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(payToAgent) as payToAgent'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['<', 'issueDate', date('Y-m-d')])
                ->andWhere(['<>', 'type', 'Refund'])->one();

            $plTicketDataRefundData = Tickets::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(segment) as segment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(payToAgent) as payToAgent'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['<', 'issueDate', date('Y-m-d')])
                ->andWhere(['type' => 'Refund'])
                ->orderBy('total DESC')->one();
            $plPackageData = Packages::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['<', 'issueDate', date('Y-m-d')])
                ->one();
            $plPackageData->totalPayable = PackageSuppliers::find()
                ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['<', 'issueDate', date('Y-m-d')])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

            $plVisaData = Visas::find()
                ->select([
                    new Expression('SUM(totalQty) as total'),
                    new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['<', 'issueDate', date('Y-m-d')])
                ->one();
            $plVisaData->totalPayable = VisaSuppliers::find()
                ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['<', 'issueDate', date('Y-m-d')])->all(), 'id', 'id')])->one()->totalPayable;

            $plExpensesData = Expense::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(amount) as amount'),
                    new Expression('SUM(paidAmount) as paidAmount'),
                    new Expression('SUM(dueAmount) as dueAmount'), 'catId', 'dateOfTransaction'])
                ->where(['<', 'date(dateOfTransaction)', date('Y-m-d')])
                ->one();

            $ait = ((double)$plTicketData->baseFare + (double)$plTicketData->tax + (double)$plTicketData->otherTax) * 0.003;
            $gross = ((double)$plTicketData->baseFare + (double)$plTicketData->tax + (double)$plTicketData->otherTax + $ait);
            $quoteAmount = (double)$plTicketData->quoteAmount;
            $discount = ($gross - $quoteAmount);

            $commission = (double)$plTicketData->commissionReceived;
            $incentive = (double)$plTicketData->incentiveReceived;

            //$airTicket = ((double)$plTicketData->quoteAmount + (double)$plTicketDataRefundData->quoteAmount) + $discount;
            $airTicket = (($commission + $incentive - $ait));
            $visa = (double)$plVisaData->quoteAmount - (double)$plVisaData->totalCostOfSale;
            $package = (double)$plPackageData->quoteAmount - (double)$plPackageData->totalCostOfSale;
            $totalIncome = ($airTicket + $visa + $package);

            // expense calculation
            $tax = (double)$plTicketData->tax;
            $otherTax = (double)$plTicketData->otherTax;
            $baseFare = (double)$plTicketData->baseFare;
            $serviceCharge = ((double)$plTicketData->serviceCharge + (double)$plTicketDataRefundData->serviceCharge);
            $visaExp = (double)$plVisaData->totalCostOfSale;
            $packageExp = (double)$plPackageData->totalCostOfSale;
            $totalExp = ($tax + $otherTax + $baseFare + $ait + $serviceCharge + $visaExp + $packageExp + $discount);

            $totalIncome = ($airTicket + $visa + $package + $commission + $incentive);
            $monthWiseData[date('Y-m')]['income'] = $totalIncome;
            $monthWiseData[date('Y-m')]['serviceExpense'] = $totalExp;
            $monthWiseData[date('Y-m')]['otherExpense'] = $plExpensesData->amount;
        }
        return $this->render('bs', [
            'data' => $monthWiseData
        ]);
    }

    public
    function actionCashFlow($dateRange = '')
    {
        if (!is_null($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);

            $date1 = DateTime::createFromFormat('Y-m-d', $start_date);
            $date2 = DateTime::createFromFormat('Y-m-d', $end_date);
            $diff = $date1->diff($date2)->m;
            if ($diff >= 1) {
                $start = (new DateTime($start_date))->modify('first day of this month');
                $end = (new DateTime($end_date))->modify('first day of next month');
                $interval = DateInterval::createFromDateString('1 month');
                $period = new DatePeriod($start, $interval, $end);

                foreach ($period as $dt) {
                    $monthWiseData[$dt->format("Y-m")]['figure'][] = ArrayHelper::map(MonthlyFigure::find()
                        ->select([new Expression('SUM(amount) as amount'), 'title'])
                        ->where(['between', 'month', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->groupBy(['title'])->all(), 'title', 'amount');

                    $monthWiseData[$dt->format("Y-m")]['figure'][] = ArrayHelper::map(MonthlyFigure::find()
                        ->select([new Expression('SUM(amount) as amount'), 'title'])
                        ->where(['between', 'month', date('Y-m-01', strtotime('-1 month', strtotime($dt->format("Y-m-d")))), date('Y-m-t', strtotime('-1 month', strtotime($dt->format("Y-m-d"))))])
                        ->groupBy(['title'])->all(), 'title', 'amount');

                    $monthWiseData[$dt->format("Y-m")]['ticket'][] = Tickets::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(payToAgent) as payToAgent'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                            new Expression('SUM(baseFare) as baseFare'),
                            new Expression('SUM(tax) as tax'),
                            new Expression('SUM(otherTax) as otherTax'),
                            new Expression('SUM(serviceCharge) as serviceCharge'),
                            new Expression('SUM(commissionReceived) as commissionReceived'),
                            new Expression('SUM(incentiveReceived) as incentiveReceived'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->andWhere(['<>', 'type', 'Refund'])->one();

                    $monthWiseData[$dt->format("Y-m")]['ticket'][] = Tickets::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(payToAgent) as payToAgent'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($dt->format("Y-m-d")))), date('Y-m-t', strtotime('-1 month', strtotime($dt->format("Y-m-d"))))])->one();

                    $monthWiseData[$dt->format("Y-m")]['package'][] = Packages::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->one();
                    $monthWiseData[$dt->format("Y-m")]['package'][] = Packages::find()
                        ->select([
                            new Expression('COUNT(id) as total'),
                            new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($dt->format("Y-m-d")))), date('Y-m-t', strtotime('-1 month', strtotime($dt->format("Y-m-d"))))])
                        ->one();

                    $monthWiseData[$dt->format("Y-m")]['package'][0]->totalPayable = PackageSuppliers::find()
                        ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                        ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

                    $monthWiseData[$dt->format("Y-m")]['package'][1]->totalPayable = PackageSuppliers::find()
                        ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                        ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($dt->format("Y-m-d")))), date('Y-m-t', strtotime('-1 month', strtotime($dt->format("Y-m-d"))))])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

                    $monthWiseData[$dt->format("Y-m")]['visa'][] = Visas::find()
                        ->select([
                            new Expression('SUM(totalQty) as total'),
                            new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->one();
                    $monthWiseData[$dt->format("Y-m")]['visa'][] = Visas::find()
                        ->select([
                            new Expression('SUM(totalQty) as total'),
                            new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                            new Expression('SUM(quoteAmount) as quoteAmount'),
                            new Expression('SUM(receivedAmount) as receivedAmount'),
                            new Expression('SUM(netProfit) as netProfit')])
                        ->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($dt->format("Y-m-d")))), date('Y-m-t', strtotime('-1 month', strtotime($dt->format("Y-m-d"))))])
                        ->one();
                    $monthWiseData[$dt->format("Y-m")]['visa'][0]->totalPayable = VisaSuppliers::find()
                        ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                        ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])->all(), 'id', 'id')])->one()->totalPayable;

                    $monthWiseData[$dt->format("Y-m")]['visa'][1]->totalPayable = VisaSuppliers::find()
                        ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                        ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($dt->format("Y-m-d")))), date('Y-m-t', strtotime('-1 month', strtotime($dt->format("Y-m-d"))))])->all(), 'id', 'id')])->one()->totalPayable;

                    $monthWiseData[$dt->format("Y-m")]['asset'] = FixedAsset::find()
                        ->select([new Expression('SUM(paidAmount) as paidAmount')])
                        ->where(['between', 'date(acquisitionDate)', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->andWhere(['isDisposed' => 0])
                        ->one();

                    $monthWiseData[$dt->format("Y-m")]['expense'] = Expense::find()
                        ->select([new Expression('SUM(paidAmount) as paidAmount')])
                        ->where(['between', 'date(dateOfTransaction)', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                        ->andWhere(['<>', 'catId', 3])->one()->paidAmount;

                    $monthWiseData[$dt->format("Y-m")]['cash'] = MonthlyBankClosing::find()
                        ->select([new Expression('SUM(amount) as amount')])
                        ->where(['between', 'month', date('Y-m-01', strtotime('-1 month', strtotime($dt->format("Y-m-d")))), date('Y-m-t', strtotime('-1 month', strtotime($dt->format("Y-m-d"))))])
                        ->one()->amount;
                }
            } else {
                $monthWiseData[date('Y-m', strtotime($start_date))]['figure'][] = ArrayHelper::map(MonthlyFigure::find()
                    ->select([new Expression('SUM(amount) as amount'), 'title'])
                    ->where(['between', 'month', $start_date, $end_date])
                    ->groupBy(['title'])->all(), 'title', 'amount');

                $monthWiseData[date('Y-m', strtotime($start_date))]['figure'][] = ArrayHelper::map(MonthlyFigure::find()
                    ->select([new Expression('SUM(amount) as amount'), 'title'])
                    ->where(['between', 'month', date('Y-m-01', strtotime('-1 month', strtotime($start_date))), date('Y-m-t', strtotime('-1 month', strtotime($end_date)))])
                    ->groupBy(['title'])->all(), 'title', 'amount');

                $monthWiseData[date('Y-m', strtotime($start_date))]['ticket'][] = Tickets::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(segment) as segment'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(payToAgent) as payToAgent'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', $start_date, $end_date])
                    ->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['ticket'][] = Tickets::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(segment) as segment'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(payToAgent) as payToAgent'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($start_date))), date('Y-m-t', strtotime('-1 month', strtotime($end_date)))])
                    ->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['package'][] = Packages::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', $start_date, $end_date])
                    ->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['package'][] = Packages::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($start_date))), date('Y-m-t', strtotime('-1 month', strtotime($end_date)))])
                    ->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['package'][0]->totalPayable = PackageSuppliers::find()
                    ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                    ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['between', 'issueDate', $start_date, $end_date])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

                $monthWiseData[date('Y-m', strtotime($start_date))]['package'][1]->totalPayable = PackageSuppliers::find()
                    ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                    ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($start_date))), date('Y-m-t', strtotime('-1 month', strtotime($end_date)))])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

                $monthWiseData[date('Y-m', strtotime($start_date))]['visa'][] = Visas::find()
                    ->select([
                        new Expression('SUM(totalQty) as total'),
                        new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', $start_date, $end_date])
                    ->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['visa'][] = Visas::find()
                    ->select([
                        new Expression('SUM(totalQty) as total'),
                        new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(netProfit) as netProfit')])
                    ->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($start_date))), date('Y-m-t', strtotime('-1 month', strtotime($end_date)))])
                    ->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['visa'][0]->totalPayable = VisaSuppliers::find()
                    ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                    ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['between', 'issueDate', $start_date, $end_date])->all(), 'id', 'id')])->one()->totalPayable;

                $monthWiseData[date('Y-m', strtotime($start_date))]['visa'][1]->totalPayable = VisaSuppliers::find()
                    ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                    ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['between', 'issueDate', date('Y-m-01', strtotime('-1 month', strtotime($start_date))), date('Y-m-t', strtotime('-1 month', strtotime($end_date)))])->all(), 'id', 'id')])->one()->totalPayable;

                $monthWiseData[date('Y-m', strtotime($start_date))]['asset'] = FixedAsset::find()
                    ->select([new Expression('SUM(paidAmount) as paidAmount')])
                    ->where(['between', 'date(acquisitionDate)', $start_date, $end_date])
                    ->andWhere(['isDisposed' => 0])
                    ->one();

                $monthWiseData[date('Y-m', strtotime($start_date))]['expense'] = Expense::find()
                    ->select([new Expression('SUM(paidAmount) as paidAmount')])
                    ->where(['between', 'date(dateOfTransaction)', $start_date, $end_date])
                    ->andWhere(['<>', 'catId', 3])->one()->paidAmount;

                $monthWiseData[date('Y-m', strtotime($start_date))]['cash'] = MonthlyBankClosing::find()
                    ->select([new Expression('SUM(amount) as amount'), 'month'])
                    ->where(['between', 'month', date('Y-m-01', strtotime('-1 month', strtotime($start_date))), date('Y-m-t', strtotime('-1 month', strtotime($end_date)))])
                    ->one()->amount;
            }

        } else {
            $monthWiseData[date('Y-m')]['figure'][] = ArrayHelper::map(MonthlyFigure::find()
                ->select([new Expression('SUM(amount) as amount'), 'title'])
                ->where(['between', 'month', date('Y-m-01'), date('Y-m-t')])
                ->groupBy(['title'])->all(), 'title', 'amount');

            $monthWiseData[date('Y-m')]['figure'][] = ArrayHelper::map(MonthlyFigure::find()
                ->select([new Expression('SUM(amount) as amount'), 'title'])
                ->where(['between', 'month', date('Y-m-01', strtotime('last month')), date('Y-m-t', strtotime('last month'))])
                ->groupBy(['title'])->all(), 'title', 'amount');

            $monthWiseData[date('Y-m')]['ticket'][] = Tickets::find()
                ->select([new Expression('COUNT(id) as total'),
                    new Expression('SUM(segment) as segment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(payToAgent) as payToAgent'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['between', 'issueDate', date('Y-m-01'), date('Y-m-t')])->one();

            $monthWiseData[date('Y-m')]['ticket'][] = Tickets::find()
                ->select([new Expression('COUNT(id) as total'),
                    new Expression('SUM(segment) as segment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(payToAgent) as payToAgent'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(payToAgent-paidAmount) as payableToAgent'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['between', 'issueDate', date('Y-m-01', strtotime('last month')), date('Y-m-t', strtotime('last month'))])->one();

            $monthWiseData[date('Y-m')]['package'][] = Packages::find()
                ->select([new Expression('COUNT(id) as total'),
                    new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['between', 'issueDate', date('Y-m-01'), date('Y-m-t')])->one();

            $monthWiseData[date('Y-m')]['package'][] = Packages::find()
                ->select([new Expression('COUNT(id) as total'),
                    new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount - receivedAmount) as sum'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['between', 'issueDate', date('Y-m-01', strtotime('last month')), date('Y-m-t', strtotime('last month'))])->one();

            $monthWiseData[date('Y-m')]['package'][0]->totalPayable = PackageSuppliers::find()
                ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['between', 'issueDate', date('Y-m-01'), date('Y-m-t')])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

            $monthWiseData[date('Y-m')]['package'][1]->totalPayable = PackageSuppliers::find()
                ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                ->where(['groupId' => ArrayHelper::map(Packages::find()->where(['between', 'issueDate', date('Y-m-01', strtotime('last month')), date('Y-m-t', strtotime('last month'))])->all(), 'id', 'invoiceNumber')])->one()->totalPayable;

            $monthWiseData[date('Y-m')]['visa'][] = Visas::find()
                ->select([new Expression('SUM(totalQty) as total'),
                    new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['between', 'issueDate', date('Y-m-01'), date('Y-m-t')])->one();

            $monthWiseData[date('Y-m')]['visa'][] = Visas::find()
                ->select([new Expression('SUM(totalQty) as total'),
                    new Expression('SUM(totalCostOfSale) as totalCostOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(netProfit) as netProfit')])
                ->where(['between', 'issueDate', date('Y-m-01', strtotime('last month')), date('Y-m-t', strtotime('last month'))])->one();

            $monthWiseData[date('Y-m')]['visa'][0]->totalPayable = VisaSuppliers::find()
                ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['between', 'issueDate', date('Y-m-01'), date('Y-m-t')])->all(), 'id', 'id')])->one()->totalPayable;

            $monthWiseData[date('Y-m')]['visa'][1]->totalPayable = VisaSuppliers::find()
                ->select([new Expression('SUM(costOfSale - paidAmount) as totalPayable')])
                ->where(['visaId' => ArrayHelper::map(Visas::find()->where(['between', 'issueDate', date('Y-m-01', strtotime('last month')), date('Y-m-t', strtotime('last month'))])->all(), 'id', 'id')])->one()->totalPayable;

            $monthWiseData[date('Y-m')]['asset'] = FixedAsset::find()
                ->select([new Expression('SUM(paidAmount) as paidAmount')])
                ->where(['between', 'date(acquisitionDate)', date('Y-m-01'), date('Y-m-t')])
                ->andWhere(['isDisposed' => 0])
                ->one();

            $expenseData = [];
            $expenses = Expense::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(amount) as amount'),
                    new Expression('SUM(paidAmount) as paidAmount'),
                    new Expression('SUM(dueAmount) as dueAmount'), 'subCatId', 'dateOfTransaction'])
                ->where(['between', 'date(dateOfTransaction)', date('Y-m-01'), date('Y-m-t')])
                ->groupBy(['subCatId'])
                ->orderBy('total DESC')
                ->all();
            foreach ($expenses as $expense) {
                $subcat = ExpenditureSubCat::findOne(['id' => $expense->subCatId])->title;
                $expenseData[$subcat] = $expense;
            }

            $monthWiseData[date('Y-m')]['expense'] = Expense::find()
                ->select([new Expression('SUM(paidAmount) as paidAmount')])
                ->where(['between', 'date(dateOfTransaction)', date('Y-m-01'), date('Y-m-t')])
                ->andWhere(['<>', 'catId', 3])->one()->paidAmount;

            $monthWiseData[date('Y-m')]['cash'] = MonthlyBankClosing::find()
                ->select([new Expression('SUM(amount) as amount'), 'month'])
                ->where(['between', 'month', date('Y-m-01', strtotime('last month')), date('Y-m-t', strtotime('last month'))])
                ->one()->amount;
        }

        return $this->render('cf', [
            'data' => $monthWiseData
        ]);
    }
}