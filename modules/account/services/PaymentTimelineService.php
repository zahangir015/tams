<?php

namespace app\modules\account\services;

use app\modules\account\models\ServicePaymentTimeline;

class PaymentTimelineService
{
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
}