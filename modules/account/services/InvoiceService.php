<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\models\InvoiceDetail;
use app\modules\account\models\ServicePaymentTimeline;
use app\modules\account\repositories\InvoiceRepository;
use app\modules\account\repositories\PaymentTimelineRepository;
use app\modules\sale\models\Customer;
use yii\db\Exception;

class InvoiceService
{
    public static function autoInvoice(int $customerId, array $services, int $group, mixed $user): array
    {
        $invoice = new Invoice();
        $invoice->date = date('Y-m-d');
        $invoice->expectedPaymentDate = $invoice->date;
        $invoice->customerId = $customerId;
        $invoice->invoiceNumber = Helper::invoiceNumber();
        $invoice->dueAmount = array_sum(array_column($services, 'dueAmount'));;
        $invoice->paidAmount = 0;
        $invoice->remarks = 'Auto generated invoice';
        $invoice->discountedAmount = 0;
        $invoice->status = GlobalConstant::ACTIVE_STATUS;

        // Invoice data process
        $invoice = InvoiceRepository::store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice creation failed - ' . Helper::processErrorMessages($invoice->getErrors())];
        }

        // Service process
        $serviceProcessResponse = self::serviceProcess($invoice, $services);
        if ($serviceProcessResponse['error']) {
            return ['error' => true, 'message' => $serviceProcessResponse['message']];
        }

        // Customer Ledger process
        $ledgerRequestData = [
            'title' => 'Service Purchase',
            'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
            'refId' => $invoice->customerId,
            'refModel' => Customer::class,
            'subRefId' => $invoice->id,
            'subRefModel' => $invoice::class,
            'debit' => array_sum(array_column($services, 'dueAmount')),
            'credit' => 0
        ];
        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'message' => 'Invoice created successfully', 'data' => $invoice];
    }

    private static function serviceProcess(Invoice $invoice, array $services): array
    {
        $invoiceDetailBatchData = [];
        $paymentTimelineBatchData = [];
        foreach ($services as $singleService) {
            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->invoiceId = $invoice->id;
            if (!$invoiceDetail->load(['InvoiceDetail' => $singleService]) || !$invoiceDetail->validate()) {
                return ['error' => true, 'message' => 'Invoice Details validation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
            }
            $invoiceDetailBatchData[] = $invoiceDetail->getAttributes();
            if (!$invoiceDetail->load(['InvoiceDetail' => $singleService]) || !$invoiceDetail->validate()) {
                return ['error' => true, 'message' => 'Service payment timeline validation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
            }

            // Payment timeline process
            $processedData = PaymentTimelineService::processData($invoice, $singleService);
            $paymentTimelineBatchData = array_merge($paymentTimelineBatchData, $processedData);

            // service update
            $serviceObject = $singleService['refModel']::findOne(['id' => $singleService['refId']]);
            if (!$serviceObject) {
                return ['error' => true, 'message' => 'Service not found'];
            }
            $serviceObject->invoiceId = $invoice->id;
            if (!$serviceObject->update()) {
                return ['error' => true, 'message' => 'Service update failed - ' . Helper::processErrorMessages($serviceObject->getErrors())];
            }
        }

        // Invoice Details insert process
        if (empty($invoiceDetailBatchData)) {
            return ['error' => true, 'message' => 'Invoice Detail Batch Data can not be empty.'];
        }
        if (!InvoiceRepository::batchStore(InvoiceDetail::tableName(), array_keys($invoiceDetailBatchData[0]), $invoiceDetailBatchData)) {
            return ['error' => true, 'message' => 'Invoice Details batch insert failed'];
        }

        // Service Payment timeline batch insert
        $paymentTimelineProcessResponse = PaymentTimelineService::batchInsert($paymentTimelineBatchData);
        if ($paymentTimelineProcessResponse['error']){
            return $paymentTimelineProcessResponse;
        }

        return ['error' => false, 'message' => 'Service process done.'];
    }
}