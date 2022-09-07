<?php

namespace app\modules\sale\repositories;

use app\components\GlobalConstant;
use app\modules\sale\models\ticket\Ticket;
use yii\db\ActiveRecord;

class FlightRepository
{
    public function store(ActiveRecord $object): ActiveRecord
    {
        $object->save();
        return $object;
    }

    public function findOneTicket(string $uid, $withArray = []): ActiveRecord
    {
        $query = Ticket::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }

        return $query->where(['uid' => $uid])->one();
    }

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