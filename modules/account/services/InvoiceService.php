<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\models\InvoiceDetail;
use app\modules\account\models\ServicePaymentTimeline;
use app\modules\account\repositories\InvoiceRepository;
use app\modules\account\repositories\PaymentTimelineRepository;
use app\modules\admin\models\User;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\repositories\HolidayRepository;
use PhpParser\Node\Expr\Cast\Double;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class InvoiceService
{
    private InvoiceRepository $invoiceRepository;

    public function __construct()
    {
        $this->invoiceRepository = new InvoiceRepository();
    }

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

    public static function autoInvoiceForRefund(Invoice $invoice, array $services, User $user): array
    {
        // Invoice Details process
        $invoiceDetail = new InvoiceDetail();
        if (!$invoiceDetail->load($services) || !$invoiceDetail->save()) {
            return ['error' => true, 'message' => 'Invoice Detail create failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetailUpdateResponse = InvoiceDetail::find()->where(['refId' => $services['parentId'], 'refModel' => $services['refModel']])->one();
        $motherInvoiceDetailUpdateResponse->status = GlobalConstant::REFUND_REQUESTED_STATUS;
        if (!$motherInvoiceDetailUpdateResponse->save()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed'];
        }

        // Invoice due update
        $invoiceDue = InvoiceDetail::find()->select([new Expression('SUM(due) AS due')])->where(['status' => GlobalConstant::ACTIVE_STATUS])->andWhere(['invoiceId' => $invoiceDetail->invoiceId])->asArray()->all();
        if (!$invoiceDue) {
            return ['status' => false, 'message' => 'Mother Invoice details update failed'];
        }

        $invoice->due = $invoiceDue[0]['due'];
        if (!$invoice->save()) {
            return ['status' => false, 'message' => 'Invoice due update failed - ' . Helper::processErrorMessages($invoice->getErrors())];
        }

        // Customer Ledger process
        $ledgerRequestData = [
            'title' => 'Service Refund',
            'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
            'refId' => $invoice->customerId,
            'refModel' => Customer::className(),
            'subRefId' => $invoice->id,
            'subRefModel' => $invoice::className(),
            'debit' => ($invoiceDetail->due > 0) ? $invoiceDetail->due : 0,
            'credit' => ($invoiceDetail->due > 0) ? 0 : $invoiceDetail->due
        ];
        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['status' => true, 'data' => $invoiceDetail];
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
        if ($paymentTimelineProcessResponse['error']) {
            return $paymentTimelineProcessResponse;
        }

        return ['error' => false, 'message' => 'Service process done.'];
    }

    public static function addRefundServiceToInvoice(ActiveRecord $newRefundService): array
    {
        $invoiceDetail = new InvoiceDetail();
        $invoiceDetail->invoiceId = $newRefundService->invoiceId;
        $invoiceDetail->dueAmount = ($newRefundService->quoteAmount - $newRefundService->receivedAmount);
        $invoiceDetail->paidAmount = $newRefundService->receivedAmount;
        $invoiceDetail->refId = $newRefundService->id;
        $invoiceDetail->refModel = $newRefundService::class;
        $invoiceDetail->status = GlobalConstant::ACTIVE_STATUS;
        $invoiceDetail = InvoiceRepository::store($invoiceDetail);
        if ($invoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice Detail creation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetail = InvoiceRepository::findOne(['refId' => $newRefundService->motherTicketId, 'refModel' => $newRefundService::class], InvoiceDetail::class, []);
        $motherInvoiceDetail->status = 2;
        $motherInvoiceDetail = InvoiceRepository::store($motherInvoiceDetail);
        if ($motherInvoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed - ' . Helper::processErrorMessages($motherInvoiceDetail->getErrors())];
        }

        // Invoice due update
        $invoice = InvoiceRepository::findOne(['id' => $newRefundService->invoiceId], Invoice::class, ['details']);
        $invoiceDetailArray = ArrayHelper::toArray($invoice->details);
        $invoice->dueAmount = (double)array_sum(array_column($invoiceDetailArray, 'dueAmount'));
        $invoice = InvoiceRepository::store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice due update failed - ' . Helper::processErrorMessages($invoice->getErrors())];
        }

        // Customer Ledger process
        $ledgerRequestData = [
            'title' => 'Service Refund',
            'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
            'refId' => $invoice->customerId,
            'refModel' => Customer::class,
            'subRefId' => $invoice->id,
            'subRefModel' => Invoice::class,
            'debit' => ($invoiceDetail->dueAmount > 0) ? $invoiceDetail->dueAmount : 0,
            'credit' => ($invoiceDetail->dueAmount > 0) ? 0 : $invoiceDetail->dueAmount
        ];

        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => $ledgerRequestResponse['error'], 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail];
    }

    public static function checkAndDetectPaymentStatus($due, $amount): string
    {
        $paymentStatus = NULL;
        $difference = abs(($due - $amount));
        if ($difference <= GlobalConstant::GRACE_DISCOUNT) {
            $paymentStatus = GlobalConstant::PAYMENT_STATUS['Full Paid'];
        } elseif ($amount > 0 && $due > 0) {
            $paymentStatus = GlobalConstant::PAYMENT_STATUS['Partially Paid'];
        } elseif ($amount == 0 && $due > 0) {
            $paymentStatus = GlobalConstant::PAYMENT_STATUS['Due'];
        }

        return $paymentStatus;
    }

    public function updateInvoice($invoice, $services)
    {

    }
}