<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\models\Invoice;
use app\modules\account\models\ServicePaymentTimeline;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\models\visa\Visa;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class SaleService
{
    public static function serviceUpdate(array $services): array
    {
        foreach ($services as $serviceArray) {
            if (empty($serviceArray['refModel']) || empty($serviceArray['query'])) {
                return ['error' => true, 'message' => "refModel or query not found"];
            }
            $serviceObject = $serviceArray['refModel']::find()->where($serviceArray['query'])->one();

            if (!$serviceObject) {
                return ['error' => true, 'message' => "The requested data does not exist with reference service object: {$serviceArray['refModel']} and id : {$serviceArray['query']}"];
            }

            if (empty($serviceArray['data']['paymentStatus'])) {
                $serviceArray['data']['paymentStatus'] = $serviceObject->paymentStatus;
            }
            $serviceObject->setAttributes($serviceArray['data']);
            if (!$serviceObject->save()) {
                return ['error' => true, 'message' => "Service update failed reference service object: {$serviceArray['refModel']} - " . Utilities::processErrorMessages($serviceObject->errors)];
            }
        }

        return ['error' => false, 'message' => 'Services updated.'];
    }

    public static function serviceDelete(array $services): array
    {
        foreach ($services as $serviceArray) {
            if (empty($serviceArray['refModel']) || empty($serviceArray['query'])) {
                return ['error' => true, 'message' => "refModel or query not found"];
            }
            $serviceObject = $serviceArray['refModel']::find()->where($serviceArray['query'])->one();

            if (!$serviceObject) {
                return ['error' => true, 'message' => "The requested data does not exist with reference service object: {$serviceArray['refModel']} and id : {$serviceArray['query']}"];
            }

            if (empty($serviceArray['data']['paymentStatus'])) {
                $serviceArray['data']['paymentStatus'] = $serviceObject->paymentStatus;
            }
            $serviceObject->setAttributes($serviceArray['data']);
            if (!$serviceObject->save()) {
                return ['error' => true, 'message' => "Service update failed reference service object: {$serviceArray['refModel']} - " . Utilities::processErrorMessages($serviceObject->errors)];
            }
        }

        return ['error' => false, 'message' => 'Services updated.'];
    }

    public static function servicePaymentTimelineProcess(Invoice $invoice, array $serviceData)
    {
        // Customer service payment timeline
        $customerServicePaymentTimeline = new ServicePaymentTimeline();
        $customerServicePaymentTimeline->subRefId = $invoice->id;
        $customerServicePaymentTimeline->subRefModel = $invoice::class;
        $customerServicePaymentTimeline->date = $invoice->date;
        if (!$customerServicePaymentTimeline->load(['ServicePaymentTimeline' => $serviceData]) || !$customerServicePaymentTimeline->validate()) {
            return ['error' => true, 'message' => 'Customer Service payment timeline validation failed - ' . Utilities::processErrorMessages($customerServicePaymentTimeline->getErrors())];
        }
        $paymentTimelineBatchData[] = $customerServicePaymentTimeline->getAttributes();

        // Supplier service payment timeline
        $supplierServicePaymentTimeline = new ServicePaymentTimeline();
        $supplierServicePaymentTimeline->subRefId = $invoice->id;
        $supplierServicePaymentTimeline->date = $invoice->date;
        if (!$supplierServicePaymentTimeline->load(['ServicePaymentTimeline' => $serviceData['supplierData'][0]]) || !$supplierServicePaymentTimeline->validate()) {
            return ['error' => true, 'message' => 'Supplier Service payment timeline validation failed - ' . Utilities::processErrorMessages($supplierServicePaymentTimeline->getErrors())];
        }
        $paymentTimelineBatchData[] = $supplierServicePaymentTimeline->getAttributes();


    }

    public static function updatedServiceRelatedData(ActiveRecord $model, array $services): array
    {
        // Invoice update
        $invoiceUpdateResponse = InvoiceService::updateInvoice($model->invoice, $services);
        if ($invoiceUpdateResponse['error']) {
            return $invoiceUpdateResponse;
        }

        // Ledger update
        $customerLedgerUpdateData = [
            'refId' => $model->customerId,
            'refModel' => Customer::class,
            'subRefId' => $model->invoiceId,
            'subRefModel' => Invoice::class,
            'debit' => $model->quoteAmount,
            'credit' => 0,
            'date' => $invoiceUpdateResponse['data']->date,
        ];
        $ledgerUpdateResponse = LedgerService::updateLedger($customerLedgerUpdateData);
        if ($ledgerUpdateResponse['error']) {
            return ['error' => true, 'message' => $ledgerUpdateResponse['message']];
        }

        return ['error' => false, 'message' => 'Invoice and ledger updated successfully'];
    }

    public static function dashboardReport(): array
    {
        // Last week date range
        /*$previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last saturday midnight",$previous_week);
        $end_week = strtotime("next friday",$start_week);
        $start_week = date("Y-m-d",$start_week);
        $end_week = date("Y-m-d",$end_week);

        $lastWeekTicketSalesData = Ticket::find()
            ->joinWith(['ticketSupplier'])
            ->select([
                new Expression('COUNT(ticket.id) as total'),
                new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                new Expression('SUM(ticket.costOfSale) as costOfSale'),
                new Expression('SUM(ticket.netProfit) as netProfit'),
            ])
            ->where(['<=', 'ticket.refundRequestDate', $end_week])
            ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
            ->andWhere(['between', 'ticket.issueDate', $start_week, $end_week])
            ->andWhere(['ticket.agencyId' => Yii::$app->user->identity->agencyId])
            ->orderBy('total DESC')
            ->asArray()
            ->one();

        $holidaySalesData = Holiday::find()
            ->joinWith(['holidaySuppliers'])
            ->select([
                new Expression('COUNT(holiday.id) as total'),
                new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                new Expression('SUM(holiday.costOfSale) as costOfSale'),
                new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                new Expression('SUM(holiday.netProfit) as netProfit'),
            ])
            ->where(['<=', 'holiday.refundRequestDate', $end_week])
            ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
            ->andWhere(['between', 'holiday.issueDate', $start_week, $end_week])
            ->andWhere(['holiday.agencyId' => Yii::$app->user->identity->agencyId])
            ->orderBy('total DESC')
            ->asArray()
            ->one();

        $hotelSalesData = Hotel::find()
            ->joinWith(['hotelSuppliers'])
            ->select([
                new Expression('COUNT(hotel.id) as total'),
                new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                new Expression('SUM(hotel.costOfSale) as costOfSale'),
                new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                new Expression('SUM(hotel.netProfit) as netProfit'),
            ])
            ->where(['<=', 'hotel.refundRequestDate', $end_week])
            ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
            ->andWhere(['between', 'hotel.issueDate', $start_week, $end_week])
            ->andWhere(['hotel.agencyId' => Yii::$app->user->identity->agencyId])
            ->orderBy('total DESC')
            ->asArray()
            ->one();

        $visaSalesData = Visa::find()
            ->joinWith(['visaSuppliers'])
            ->select([
                new Expression('COUNT(visa.id) as total'),
                new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                new Expression('SUM(visa.costOfSale) as costOfSale'),
                new Expression('SUM(visa_supplier.paidAmount) as paidAmount'),
                new Expression('SUM(visa.netProfit) as netProfit'),
            ])
            ->where(['<=', 'visa.refundRequestDate', $end_week])
            ->orWhere(['IS', 'visa.refundRequestDate', NULL])
            ->andWhere(['between', 'visa.issueDate', $start_week, $end_week])
            ->andWhere(['visa.agencyId' => Yii::$app->user->identity->agencyId])
            ->orderBy('total DESC')
            ->asArray()
            ->one();

        echo $start_week.' '.$end_week ;

        //Last Month date range
        $firstDayOfLastMonth = date('Y-m-d',strtotime('first day of last month'));
        $lastDayOfLastMonth = date('Y-m-d',strtotime('last day of last month'));
        echo $firstDayOfLastMonth.' '.$lastDayOfLastMonth ;
        die();*/

        $monthlyServiceSale = self::monthlySales();

        $dateRangeArray = [
            'currentDayData' => date('Y-m-d') . ' - ' . date('Y-m-d'),
            'currentMonthData' => date('Y-m-01') . ' - ' . date('Y-m-t'),
            'previousMonthData' => date('Y-m-d', strtotime('first day of last month')) . ' - ' . date('Y-m-d', strtotime('last day of last month')),
        ];


        $saleData = [
            'currentDaySales' => [
                'ticket' => $monthlyServiceSale['ticketSalesData'][date('Y-m-d')],
                'hotel' => $monthlyServiceSale['hotelSalesData'][date('Y-m-d')],
                'holiday' => $monthlyServiceSale['holidaySalesData'][date('Y-m-d')],
                'visa' => $monthlyServiceSale['visaSalesData'][date('Y-m-d')],
            ],
            'currentMonthSales' => [
                'ticket' => $monthlyServiceSale['ticketSalesData'][date('Y-m')],
                'hotel' => $monthlyServiceSale['hotelSalesData'][date('Y-m')],
                'holiday' => $monthlyServiceSale['holidaySalesData'][date('Y-m')],
                'visa' => $monthlyServiceSale['visaSalesData'][date('Y-m')],
            ],
            'previousMonthSales' => [
                'ticket' => $monthlyServiceSale['ticketSalesData'][date('Y-m', strtotime('-1 month'))],
                'hotel' => $monthlyServiceSale['hotelSalesData'][date('Y-m', strtotime('-1 month'))],
                'holiday' => $monthlyServiceSale['holidaySalesData'][date('Y-m', strtotime('-1 month'))],
                'visa' => $monthlyServiceSale['visaSalesData'][date('Y-m', strtotime('-1 month'))],
            ],
        ];

        $totalQuantity = array_sum(array_column($saleData['currentDaySales'], 'total'));
        $totalQuote = array_sum(array_column($saleData['currentDaySales'], 'quoteAmount'));
        $totalReceived = array_sum(array_column($saleData['currentDaySales'], 'receivedAmount'));
        $totalPaid = array_sum(array_column($saleData['currentDaySales'], 'paidAmount'));
        $totalCost = array_sum(array_column($saleData['currentDaySales'], 'costOfSale'));
        $totalNetProfit = array_sum(array_column($saleData['currentDaySales'], 'netProfit'));

        $totalMonthlyQuantity = array_sum(array_column($saleData['currentMonthSales'], 'total'));
        $totalMonthlyQuote = array_sum(array_column($saleData['currentMonthSales'], 'quoteAmount'));
        $totalMonthlyReceived = array_sum(array_column($saleData['currentMonthSales'], 'receivedAmount'));
        $totalMonthlyPaid = array_sum(array_column($saleData['currentMonthSales'], 'paidAmount'));
        $totalMonthlyCost = array_sum(array_column($saleData['currentMonthSales'], 'costOfSale'));
        $totalMonthlyNetProfit = array_sum(array_column($saleData['currentMonthSales'], 'netProfit'));

        $totalPreviousMonthlyQuantity = array_sum(array_column($saleData['previousMonthSales'], 'total'));
        $totalPreviousMonthlyQuote = array_sum(array_column($saleData['previousMonthSales'], 'quoteAmount'));
        $totalPreviousMonthlyReceived = array_sum(array_column($saleData['previousMonthSales'], 'receivedAmount'));
        $totalPreviousMonthlyPaid = array_sum(array_column($saleData['previousMonthSales'], 'paidAmount'));
        $totalPreviousMonthlyCost = array_sum(array_column($saleData['previousMonthSales'], 'costOfSale'));
        $totalPreviousMonthlyNetProfit = array_sum(array_column($saleData['previousMonthSales'], 'netProfit'));

        /*foreach ($dateRangeArray as $key => $dateRange) {
            list($start_date, $end_date) = explode(' - ', $dateRange);
            $date = date('jS \of F', strtotime($start_date)) . ' to ' . date('jS \of F', strtotime($end_date));

            $ticketSalesData[$key] = Ticket::find()
                ->joinWith(['ticketSupplier'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->andWhere(['ticket.agencyId' => Yii::$app->user->identity->agencyId])
                ->orderBy('total DESC')
                ->asArray()
                ->one();

            $holidaySalesData[$key] = Holiday::find()
                ->joinWith(['holidaySuppliers'])
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                ])
                ->where(['<=', 'holiday.refundRequestDate', $end_date])
                ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
                ->andWhere(['between', 'holiday.issueDate', $start_date, $end_date])
                ->andWhere(['holiday.agencyId' => Yii::$app->user->identity->agencyId])
                ->orderBy('total DESC')
                ->asArray()
                ->one();

            $hotelSalesData[$key] = Hotel::find()
                ->joinWith(['hotelSuppliers'])
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                ])
                ->where(['<=', 'hotel.refundRequestDate', $end_date])
                ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
                ->andWhere(['between', 'hotel.issueDate', $start_date, $end_date])
                ->andWhere(['hotel.agencyId' => Yii::$app->user->identity->agencyId])
                ->orderBy('total DESC')
                ->asArray()
                ->one();

            $visaSalesData[$key] = Visa::find()
                ->joinWith(['visaSuppliers'])
                ->select([
                    new Expression('COUNT(visa.id) as total'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa.costOfSale) as costOfSale'),
                    new Expression('SUM(visa_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(visa.netProfit) as netProfit'),
                ])
                ->where(['<=', 'visa.refundRequestDate', $end_date])
                ->orWhere(['IS', 'visa.refundRequestDate', NULL])
                ->andWhere(['between', 'visa.issueDate', $start_date, $end_date])
                ->andWhere(['visa.agencyId' => Yii::$app->user->identity->agencyId])
                ->orderBy('total DESC')
                ->asArray()
                ->one();
        }*/

        $topSupplierTicketSalesData = self::supplierSales($dateRangeArray);

        $topSaleSourceTicketSalesData = self::sourceSale($dateRangeArray);

        /*$saleData = [
            'date' => $date,
            'currentDaySales' => [
                'ticket' => $ticketSalesData['currentDayData'],
                'hotel' => $hotelSalesData['currentDayData'],
                'holiday' => $holidaySalesData['currentDayData'],
                'visa' => $visaSalesData['currentDayData'],
            ],
            'currentMonthSales' => [
                'ticket' => $ticketSalesData['currentMonthData'],
                'hotel' => $hotelSalesData['currentMonthData'],
                'holiday' => $holidaySalesData['currentMonthData'],
                'visa' => $visaSalesData['currentMonthData'],
            ],
            'previousMonthSales' => [
                'ticket' => $ticketSalesData['previousMonthData'],
                'hotel' => $hotelSalesData['previousMonthData'],
                'holiday' => $holidaySalesData['previousMonthData'],
                'visa' => $visaSalesData['previousMonthData'],
            ],
        ];

        $totalQuantity = array_sum(array_column($saleData['currentDaySales'], 'total'));
        $totalQuote = array_sum(array_column($saleData['currentDaySales'], 'quoteAmount'));
        $totalReceived = array_sum(array_column($saleData['currentDaySales'], 'receivedAmount'));
        $totalPaid = array_sum(array_column($saleData['currentDaySales'], 'paidAmount'));
        $totalCost = array_sum(array_column($saleData['currentDaySales'], 'costOfSale'));
        $totalNetProfit = array_sum(array_column($saleData['currentDaySales'], 'netProfit'));

        $totalMonthlyQuantity = array_sum(array_column($saleData['currentMonthSales'], 'total'));
        $totalMonthlyQuote = array_sum(array_column($saleData['currentMonthSales'], 'quoteAmount'));
        $totalMonthlyReceived = array_sum(array_column($saleData['currentMonthSales'], 'receivedAmount'));
        $totalMonthlyPaid = array_sum(array_column($saleData['currentMonthSales'], 'paidAmount'));
        $totalMonthlyCost = array_sum(array_column($saleData['currentMonthSales'], 'costOfSale'));
        $totalMonthlyNetProfit = array_sum(array_column($saleData['currentMonthSales'], 'netProfit'));

        $totalPreviousMonthlyQuantity = array_sum(array_column($saleData['previousMonthSales'], 'total'));
        $totalPreviousMonthlyQuote = array_sum(array_column($saleData['previousMonthSales'], 'quoteAmount'));
        $totalPreviousMonthlyReceived = array_sum(array_column($saleData['previousMonthSales'], 'receivedAmount'));
        $totalPreviousMonthlyPaid = array_sum(array_column($saleData['previousMonthSales'], 'paidAmount'));
        $totalPreviousMonthlyCost = array_sum(array_column($saleData['previousMonthSales'], 'costOfSale'));
        $totalPreviousMonthlyNetProfit = array_sum(array_column($saleData['previousMonthSales'], 'netProfit'));*/

        return [
            'saleData' => $saleData,
            'totalQuantity' => $totalQuantity,
            'totalQuote' => $totalQuote,
            'totalReceived' => $totalReceived,
            'totalPaid' => $totalPaid,
            'totalCost' => $totalCost,
            'totalNetProfit' => $totalNetProfit,

            'totalMonthlyQuantity' => $totalMonthlyQuantity,
            'totalMonthlyQuote' => $totalMonthlyQuote,
            'totalMonthlyReceived' => $totalMonthlyReceived,
            'totalMonthlyPaid' => $totalMonthlyPaid,
            'totalMonthlyCost' => $totalMonthlyCost,
            'totalMonthlyNetProfit' => $totalMonthlyNetProfit,

            'totalPreviousMonthlyQuantity' => $totalPreviousMonthlyQuantity,
            'totalPreviousMonthlyQuote' => $totalPreviousMonthlyQuote,
            'totalPreviousMonthlyReceived' => $totalPreviousMonthlyReceived,
            'totalPreviousMonthlyPaid' => $totalPreviousMonthlyPaid,
            'totalPreviousMonthlyCost' => $totalPreviousMonthlyCost,
            'totalPreviousMonthlyNetProfit' => $totalPreviousMonthlyNetProfit,


            'ticketPercentage' => ($totalQuote) ? ($saleData['currentDaySales']['ticket']['quoteAmount'] * 100) / $totalQuote : 0,
            'hotelPercentage' => ($totalQuote) ? ($saleData['currentDaySales']['hotel']['quoteAmount'] * 100) / $totalQuote : 0,
            'holidayPercentage' => ($totalQuote) ? ($saleData['currentDaySales']['holiday']['quoteAmount'] * 100) / $totalQuote : 0,
            'visaPercentage' => ($totalQuote) ? ($saleData['currentDaySales']['visa']['quoteAmount'] * 100) / $totalQuote : 0,

            'monthlyTicketPercentage' => ($totalMonthlyQuote) ? ($saleData['currentMonthSales']['ticket']['quoteAmount'] * 100) / $totalMonthlyQuote : 0,
            'monthlyHotelPercentage' => ($totalMonthlyQuote) ? ($saleData['currentMonthSales']['hotel']['quoteAmount'] * 100) / $totalMonthlyQuote : 0,
            'monthlyHolidayPercentage' => ($totalMonthlyQuote) ? ($saleData['currentMonthSales']['holiday']['quoteAmount'] * 100) / $totalMonthlyQuote : 0,
            'monthlyVisaPercentage' => ($totalMonthlyQuote) ? ($saleData['currentMonthSales']['visa']['quoteAmount'] * 100) / $totalMonthlyQuote : 0,

            'receivable' => number_format(ceil($totalQuote - $totalReceived)),
            'payable' => number_format(ceil($totalCost - $totalPaid)),

            'monthlyReceivable' => number_format(ceil($totalMonthlyQuote - $totalMonthlyReceived)),
            'monthlyPayable' => number_format(ceil($totalMonthlyCost - $totalMonthlyPaid)),

            'topSupplierTicketSalesData' => $topSupplierTicketSalesData,
            'topSaleSourceTicketSalesData' => $topSaleSourceTicketSalesData,
        ];

    }

    public static function monthlySales(): array
    {
        $startingMonth = strtotime(date('Y-01'));
        $end = strtotime(date('Y-m-d'));
        $count = 0;
        while ($startingMonth < $end) {
            if ($count) {
                $month = date('Y-m', $startingMonth);
                list($start_date, $end_date) = [date("$month-01"), date('Y-m-t', strtotime(date("$month-01")))];
            } else {
                $month = date('Y-m-d');
                list($start_date, $end_date) = [date("Y-m-d"), date("Y-m-d")];
            }

            $ticketSalesData[$month] = Ticket::find()
                ->joinWith(['ticketSupplier'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->andWhere(['ticket.agencyId' => Yii::$app->user->identity->agencyId])
                ->orderBy('total DESC')
                ->asArray()
                ->one();

            $holidaySalesData[$month] = Holiday::find()
                ->joinWith(['holidaySuppliers'])
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                ])
                ->where(['<=', 'holiday.refundRequestDate', $end_date])
                ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
                ->andWhere(['between', 'holiday.issueDate', $start_date, $end_date])
                ->andWhere(['holiday.agencyId' => Yii::$app->user->identity->agencyId])
                ->orderBy('total DESC')
                ->asArray()
                ->one();

            $hotelSalesData[$month] = Hotel::find()
                ->joinWith(['hotelSuppliers'])
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                ])
                ->where(['<=', 'hotel.refundRequestDate', $end_date])
                ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
                ->andWhere(['between', 'hotel.issueDate', $start_date, $end_date])
                ->andWhere(['hotel.agencyId' => Yii::$app->user->identity->agencyId])
                ->orderBy('total DESC')
                ->asArray()
                ->one();

            $visaSalesData[$month] = Visa::find()
                ->joinWith(['visaSuppliers'])
                ->select([
                    new Expression('COUNT(visa.id) as total'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa.costOfSale) as costOfSale'),
                    new Expression('SUM(visa_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(visa.netProfit) as netProfit'),
                ])
                ->where(['<=', 'visa.refundRequestDate', $end_date])
                ->orWhere(['IS', 'visa.refundRequestDate', NULL])
                ->andWhere(['between', 'visa.issueDate', $start_date, $end_date])
                ->andWhere(['visa.agencyId' => Yii::$app->user->identity->agencyId])
                ->orderBy('total DESC')
                ->asArray()
                ->one();

            if ($count) {
                $startingMonth = strtotime($month . ' + 1 month');
            } else {
                $count++;
            }
        }

        return [
            'ticketSalesData' => $ticketSalesData,
            'holidaySalesData' => $holidaySalesData,
            'hotelSalesData' => $hotelSalesData,
            'visaSalesData' => $visaSalesData,
        ];
    }

    public static function supplierSales($dateRangeArray): array
    {
        list($start_date, $end_date) = explode(' - ', $dateRangeArray['currentMonthData']);
        $supplierTicketSalesData = TicketSupplier::find()
            ->joinWith(['supplier', 'ticket'])
            ->select([
                new Expression('SUM(ticket_supplier.costOfSale) as costOfSale'),
                new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                'ticket_supplier.supplierId',
                'supplier.company'
            ])
            ->where(['<=', 'ticket_supplier.refundRequestDate', $end_date])
            ->orWhere(['IS', 'ticket_supplier.refundRequestDate', NULL])
            ->andWhere(['between', 'ticket_supplier.issueDate', $start_date, $end_date])
            ->andWhere(['ticket.agencyId' => Yii::$app->user->identity->agencyId])
            ->groupBy('ticket_supplier.supplierId')
            ->orderBy('costOfSale DESC')
            ->limit(4)
            ->asArray()
            ->all();

        $topSupplierTicketSalesData = ArrayHelper::map($supplierTicketSalesData, 'company', function ($supplierTicketSalesData) {
            return [
                'costOfSale' => $supplierTicketSalesData['costOfSale'],
                'paidAmount' => $supplierTicketSalesData['paidAmount'],
                'supplierId' => $supplierTicketSalesData['supplierId'],
            ];
        });

        $supplierArray = array_column($topSupplierTicketSalesData, 'supplierId');
        $otherSupplierTicketSalesData = TicketSupplier::find()
            ->joinWith(['supplier', 'ticket'])
            ->select([
                new Expression('SUM(ticket_supplier.costOfSale) as costOfSale'),
                new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
            ])
            ->where(['<=', 'ticket_supplier.refundRequestDate', $end_date])
            ->orWhere(['IS', 'ticket_supplier.refundRequestDate', NULL])
            ->andWhere(['between', 'ticket_supplier.issueDate', $start_date, $end_date])
            ->andWhere(['ticket.agencyId' => Yii::$app->user->identity->agencyId])
            ->andWhere(['NOT IN', 'ticket_supplier.supplierId', $supplierArray])
            ->orderBy('costOfSale DESC')
            ->asArray()
            ->one();

        $topSupplierTicketSalesData['Others'] = [
            'costOfSale' => ($otherSupplierTicketSalesData['costOfSale']) ?: 0,
            'paidAmount' => ($otherSupplierTicketSalesData['paidAmount']) ?: 0,
        ];


        /*if (!empty(array_filter($otherSupplierTicketSalesData))) {
            $topSupplierTicketSalesData['Others'] = [
                'costOfSale' => $otherSupplierTicketSalesData['costOfSale'],
                'paidAmount' => $otherSupplierTicketSalesData['paidAmount'],
            ];
        }*/

        return $topSupplierTicketSalesData;
    }

    public static function sourceSale($dateRangeArray): array
    {
        list($start_date, $end_date) = explode(' - ', $dateRangeArray['currentMonthData']);
        return Ticket::find()
            ->joinWith(['ticketSupplier'])
            ->select([
                new Expression('COUNT(ticket.id) as total'),
                new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                new Expression('SUM(ticket.costOfSale) as costOfSale'),
                new Expression('SUM(ticket.netProfit) as netProfit'),
                'bookedOnline'
            ])
            ->where(['<=', 'ticket.refundRequestDate', $end_date])
            ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
            ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
            ->andWhere(['ticket.agencyId' => Yii::$app->user->identity->agencyId])
            ->groupBy(['bookedOnline'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();
    }

    public static function chartReport()
    {

    }
}