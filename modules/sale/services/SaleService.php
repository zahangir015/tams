<?php

namespace app\modules\sale\services;

use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\models\ServicePaymentTimeline;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\account\services\PaymentTimelineService;
use yii\db\ActiveRecord;

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
                return ['error' => true, 'message' => "Service update failed reference service object: {$serviceArray['refModel']} - ".Helper::processErrorMessages($serviceObject->errors)];
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
            return ['error' => true, 'message' => 'Customer Service payment timeline validation failed - ' . Helper::processErrorMessages($customerServicePaymentTimeline->getErrors())];
        }
        $paymentTimelineBatchData[] = $customerServicePaymentTimeline->getAttributes();

        // Supplier service payment timeline
        $supplierServicePaymentTimeline = new ServicePaymentTimeline();
        $supplierServicePaymentTimeline->subRefId = $invoice->id;
        $supplierServicePaymentTimeline->date = $invoice->date;
        if (!$supplierServicePaymentTimeline->load(['ServicePaymentTimeline' => $serviceData['supplierData'][0]]) || !$supplierServicePaymentTimeline->validate()) {
            return ['error' => true, 'message' => 'Supplier Service payment timeline validation failed - ' . Helper::processErrorMessages($supplierServicePaymentTimeline->getErrors())];
        }
        $paymentTimelineBatchData[] = $supplierServicePaymentTimeline->getAttributes();


    }

    public static function updatedServiceRelatedData(ActiveRecord $model, array $services)
    {
        // Invoice update
        $invoiceUpdateResponse = InvoiceService::updateInvoice($model->invoice, $services);
        if (!$invoiceUpdateResponse['status']) {
            return ['error' => true, 'message' => $invoiceUpdateResponse['message']];
        }

        // Ledger update
        $customerLedgerUpdateData = LedgerService::formCustomerLedgerData($model);
        $customerLedgerUpdateData['date'] = $invoiceUpdateResponse['data']->date;
        $ledgerUpdateResponse = LedgerService::updateLedger($customerLedgerUpdateData);
        if (!$ledgerUpdateResponse['status']) {
            return ['error' => true, 'message' => $ledgerUpdateResponse['message']];
        }

        // update service payment details
        $servicePaymentUpdateData = [
            'refId' => $model->id,
            'refModel' => $model::className(),
            'subRefId' => $model->invoiceId,
            'subRefModel' => Invoice::class,
            'dueAmount' => $model->quoteAmount,
            'paidAmount' => 0
        ];
        $servicePaymentDetailUpdateResponse = PaymentTimelineService::updateServicePaymentDetail($servicePaymentUpdateData);
        if (!$servicePaymentDetailUpdateResponse['status']) {
            return ['error' => true, 'message' => $servicePaymentDetailUpdateResponse['message']];
        }

        return ['error' => false, 'message' => 'Invoice, ledger and service payment detail updated successfully'];
    }
}