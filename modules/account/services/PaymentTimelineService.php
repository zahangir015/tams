<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\models\ServicePaymentTimeline;
use app\modules\account\repositories\PaymentTimelineRepository;
use yii\db\ActiveRecord;

class PaymentTimelineService
{
    private PaymentTimelineRepository $paymentTimelineRepository;

    public function __construct()
    {
        $this->paymentTimelineRepository = new PaymentTimelineRepository();
    }

    public static function processData(Invoice $invoice, array $singleService): array
    {
        $paymentTimelineBatchData = [];

        // Customer service payment timeline
        $customerServicePaymentTimeline = new ServicePaymentTimeline();
        $customerServicePaymentTimeline->subRefId = $invoice->id;
        $customerServicePaymentTimeline->subRefModel = $invoice::class;
        $customerServicePaymentTimeline->date = $invoice->date;
        if (!$customerServicePaymentTimeline->load(['ServicePaymentTimeline' => $singleService]) || !$customerServicePaymentTimeline->validate()) {
            return ['error' => true, 'message' => 'Customer Service payment timeline validation failed - ' . Helper::processErrorMessages($customerServicePaymentTimeline->getErrors())];
        }
        $paymentTimelineBatchData[] = $customerServicePaymentTimeline->getAttributes();

        // Supplier service payment timeline
        foreach ($singleService['supplierData'] as $supplierData) {
            $supplierServicePaymentTimeline = new ServicePaymentTimeline();
            $supplierServicePaymentTimeline->subRefId = $invoice->id;
            $supplierServicePaymentTimeline->subRefModel = $invoice::class;
            $supplierServicePaymentTimeline->date = $invoice->date;
            if (!$supplierServicePaymentTimeline->load(['ServicePaymentTimeline' => $supplierData]) || !$supplierServicePaymentTimeline->validate()) {
                return ['error' => true, 'message' => 'Supplier Service payment timeline validation failed - ' . Helper::processErrorMessages($supplierServicePaymentTimeline->getErrors())];
            }
            $paymentTimelineBatchData[] = $supplierServicePaymentTimeline->getAttributes();
        }

        return $paymentTimelineBatchData;
    }

    public static function batchInsert(array $rowData): array
    {
        // Payment Timeline insert process
        if (empty($rowData)) {
            return ['error' => true, 'message' => 'Payment Timeline Batch Data can not be empty.'];
        }
        if (!PaymentTimelineRepository::batchStore(ServicePaymentTimeline::tableName(), array_keys($rowData[0]), $rowData)) {
            return ['error' => true, 'message' => 'Payment Timeline batch insert failed'];
        }

        return ['error' => false, 'message' => 'Payment Timeline Batch Data inserted successfully.'];
    }

    public static function store($invoice, $requestData, $user): array
    {
        $servicePaymentTimeline = new ServicePaymentTimeline();
        $servicePaymentTimeline->date = date('Y-m-d H:i:s');
        $servicePaymentTimeline->load(['ServicePaymentTimeline' => $requestData]);
        /*$servicePaymentDetail->refModel = $refModel;
        $servicePaymentDetail->refId = $refId;
        $servicePaymentDetail->amount = $amount;
        $servicePaymentDetail->subRefId = $subRefId;
        $servicePaymentDetail->subRefModel = $subRefModel;
        $servicePaymentDetail->due = $due;*/
        $servicePaymentTimeline->status = Constant::ACTIVE_STATUS;
        if (!$servicePaymentTimeline->save()) {
            return ['error' => true, 'message' => 'Service Payment Detail - ' . Utils::processErrorMessages($servicePaymentTimeline->getErrors())];
        }

        return ['error' => false, 'message' => 'Success'];
    }

    public static function update(array $data): array
    {
        $paymentDetails = ServicePaymentDetail::find()
            ->where([
                'refId' => $data['refId'],
                'refModel' => $data['refModel'],
                'subRefId' => $data['subRefId'],
                'subRefModel' => $data['subRefModel']
            ])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        if (!count($paymentDetails)) {
            return ['status' => false, 'message' => 'Service payment details not found'];
        }
        $prevRow = NULL;
        foreach ($paymentDetails as $paymentDetail) {
            if (!empty($prevRow)) {
                $paymentDetail->due = $prevRow->due - $paymentDetail->amount;
            } else {
                $paymentDetail->due = $data['due']; //quoteAmount
            }
            if (!$paymentDetail->save()) {
                return ['status' => false, 'message' => 'Payment Detail Update failed ' . Utils::processErrorMessages($paymentDetail->getErrors())];
            }
            $prevRow = $paymentDetail;
        }

        return ['status' => true, 'message' => 'Service payment detail updated'];
    }

    public static function updateServicePaymentDetail(array $servicePaymentUpdateData)
    {
    }

    public function storeServicePaymentDetailData(array $services, ActiveRecord $invoiceDetail): array
    {
        foreach ($services as $key => $service) {
            if ($key == 'parentService' && $service->paymentStatus == GlobalConstant::PAYMENT_STATUS['Full Paid']) {
                continue;
            }
            $model = new ServicePaymentTimeline();
            $model->date = date('Y-m-d');
            $model->refId = $service->id;
            $model->refModel = get_class($service);
            $model->subRefId = $invoiceDetail->invoiceId;
            $model->subRefModel = Invoice::class;
            $model->paidAmount = ($key == 'parentService') ? $service->receivedAmount : 0;
            $model->dueAmount = ($key == 'parentService') ? 0 : ($service->quoteAmount - $services['parentService']->receivedAmount);
            $model->status = GlobalConstant::ACTIVE_STATUS;
            $model = $this->paymentTimelineRepository->store($model);
            if ($model->hasErrors()) {
                return ['error' => true, 'message' => 'Service Payment details not saved - ' . Helper::processErrorMessages($model->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'service payment detail saved successfully'];
    }
}