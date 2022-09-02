<?php

namespace app\modules\sale\services;

use app\components\Helper;
use app\modules\account\models\Invoice;

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

    public static function serviceDataProcessForInvoice(Invoice $invoice, array $services, mixed $user)
    {
        foreach ($services as $service) {
            // Invoice details entry
            $invoiceDetailResponse = InvoiceDetail::storeOrUpdateInvoiceDetail($invoice->id, $service['refModel'], $service['refId'], $service['amount'], $service['due'], $user);
            if ($invoiceDetailResponse['error']) {
                return ['error' => true, 'message' => $invoiceDetailResponse['message']];
            }

            // Service Payment Details entry   for customer
            $servicePaymentDetailResponse = ServicePaymentDetail::storeServicePaymentDetail($service['refModel'], $service['refId'], $invoice::className(), $invoice->id, $service['amount'], $service['due'], $user);
            if ($servicePaymentDetailResponse['error']) {
                return ['error' => true, 'message' => $servicePaymentDetailResponse['message']];
            }
            // Service Payment Details entry  for Supplier
            if (!empty($service['supplierData'])) {
                foreach ($service['supplierData'] as $supplierDatum) {
                    $servicePaymentDetailResponse = ServicePaymentDetail::storeServicePaymentDetail($supplierDatum['refModel'], $supplierDatum['refId'], $supplierDatum['subRefModel'] ?? null, $supplierDatum['subRefId'] ?? null, $supplierDatum['amount'], $supplierDatum['due'], $user);
                    if ($servicePaymentDetailResponse['error']) {
                        return ['error' => true, 'message' => $servicePaymentDetailResponse['message']];
                    }
                }
            }
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
}