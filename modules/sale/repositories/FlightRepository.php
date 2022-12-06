<?php

namespace app\modules\sale\repositories;

use app\components\GlobalConstant;
use app\modules\sale\models\ticket\Ticket;
use app\repository\ParentRepository;
use Yii;
use yii\db\ActiveRecord;

class FlightRepository extends ParentRepository
{
    public function findAllTicket(string $query): array
    {
        return Ticket::find()
            ->select(['id', 'eTicket', 'pnrCode'])
            ->where(['like', 'eTicket', $query])
            ->orWhere(['like', 'pnrCode', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}