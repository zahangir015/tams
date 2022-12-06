<?php

namespace app\modules\account\repositories;

use app\components\GlobalConstant;
use app\modules\account\models\ServicePaymentTimeline;
use app\repository\ParentRepository;
use Yii;
use yii\db\ActiveRecord;

class PaymentTimelineRepository extends ParentRepository
{
    public function search(string $query): array
    {
        return ServicePaymentTimeline::find()
            ->select(['id', 'eTicket', 'pnrCode'])
            ->where(['like', 'eTicket', $query])
            ->orWhere(['like', 'pnrCode', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}