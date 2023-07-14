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
        //TODO Current days sale for all services
        $ticketSalesData = Ticket::find()
            ->joinWith(['ticketSupplier'])
            ->select([
                new Expression('COUNT(ticket.id) as total'),
                new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                new Expression('SUM(ticket.costOfSale) as costOfSale'),
                new Expression('SUM(ticket.netProfit) as netProfit'),
            ])
            ->where(['<=', 'ticket.refundRequestDate', date("Y-m-d")])
            ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
            ->andWhere(['ticket.issueDate' => date("Y-m-d")])
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
            ->where(['<=', 'holiday.refundRequestDate', date("Y-m-d")])
            ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
            ->andWhere(['holiday.issueDate' => date("Y-m-d")])
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
            ->where(['<=', 'hotel.refundRequestDate', date("Y-m-d")])
            ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
            ->andWhere(['hotel.issueDate' => date("Y-m-d")])
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
            ->where(['<=', 'visa.refundRequestDate', date("Y-m-d")])
            ->orWhere(['IS', 'visa.refundRequestDate', NULL])
            ->andWhere(['visa.issueDate' => date("Y-m-d")])
            ->andWhere(['visa.agencyId' => Yii::$app->user->identity->agencyId])
            ->orderBy('total DESC')
            ->asArray()
            ->one();
        return [
            $ticketSalesData,
            $holidaySalesData,
            $hotelSalesData,
            $visaSalesData,
        ];
        //TODO total Receivable and Payable
        //TODO profit/loss

        //TODO total Sale Source
        //TODO Top Sales
        //TODO Top Suppliers

    }
}