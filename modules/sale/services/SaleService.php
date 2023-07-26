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
use app\modules\sale\models\visa\Visa;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
        $dateRangeArray = [
            'currentDayData' => date('Y-m-d') . ' - ' . date('Y-m-d'),
            'currentMonthData' => date('Y-m-01') . ' - ' . date('Y-m-t')
        ];

        foreach ($dateRangeArray as $key => $dateRange) {
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
        }


        return [
            'ticketSalesData' => $ticketSalesData,
            'holidaySalesData' => $holidaySalesData,
            'hotelSalesData' => $hotelSalesData,
            'visaSalesData' => $visaSalesData,
        ];

    }
}