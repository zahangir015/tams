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
    public static function serviceUpdate(array $data, array $updatedService = NULL): array
    {
        foreach ($data as $datum) {
            if (empty($datum['refModel']) || empty($datum['query'])) {
                return ['status' => false, 'message' => "refModel or query not found"];
            }
            $model = $datum['refModel']::find()->where($datum['query'])->one();

            if (!$model) {
                return ['status' => false, 'message' => "The requested data does not exist with reference model: {$datum['refModel']} and id : {$datum['query']}"];
            }

            if (empty($datum['data']['paymentStatus'])) {
                $datum['data']['paymentStatus'] = $model->paymentStatus;
            }
            $model->setAttributes($datum['data']);
            if (!$model->save()) {
                return ['status' => false, 'message' => Helper::processErrorMessages($model->errors)];
            }
        }

        return ['status' => true, 'message' => 'Services updated.', 'data' => $updatedService];
    }

    public static function serviceDataProcessForInvoice(Invoice $invoice, array $services, mixed $user): array
    {
        foreach ($services as $service) {
            // Invoice details entry
            $invoiceDetailResponse = InvoiceDetail::storeOrUpdateInvoiceDetail($invoice->id, $service['refModel'], $service['refId'], $service['paidAmount'], $service['dueAmount'], $user);
            if ($invoiceDetailResponse['error']) {
                return ['error' => true, 'message' => $invoiceDetailResponse['message']];
            }

            // Service Payment Details entry   for customer
            /*$servicePaymentDetailResponse = ServicePaymentDetail::storeServicePaymentDetail($service['refModel'], $service['refId'], $invoice::className(), $invoice->id, $service['paidAmount'], $service['dueAmount'], $user);
            if ($servicePaymentDetailResponse['error']) {
                return ['error' => true, 'message' => $servicePaymentDetailResponse['message']];
            }*/

            // Service Payment Details entry  for Supplier
            /*if (!empty($service['supplierData'])) {
                foreach ($service['supplierData'] as $supplierDatum) {
                    $servicePaymentDetailResponse = ServicePaymentDetail::storeServicePaymentDetail($supplierDatum['refModel'], $supplierDatum['refId'], $supplierDatum['subRefModel'] ?? null, $supplierDatum['subRefId'] ?? null, $supplierDatum['paidAmount'], $supplierDatum['dueAmount'], $user);
                    if ($servicePaymentDetailResponse['error']) {
                        return ['error' => true, 'message' => $servicePaymentDetailResponse['message']];
                    }
                }
            }*/

            // Update InvoiceId column in Service (Ticket/Hotel/Visa/Package etc) Model
            $AllServices = $service['refModel']::find()->where(['id' => $service['refId']])->all();
            foreach ($AllServices as $storedService) {
                $storedService->invoiceId = $invoice->id;
                if (!$storedService->save()) {
                    return ['error' => true, 'message' => Utils::processErrorMessages($storedService->getErrors())];
                }
            }
        }

        return ['error' => false, 'message' => 'Service data processed successfully'];
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