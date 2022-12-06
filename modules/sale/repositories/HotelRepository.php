<?php

namespace app\modules\sale\repositories;

use app\components\GlobalConstant;
use app\modules\sale\models\hotel\Hotel;
use app\repository\ParentRepository;

class HotelRepository extends ParentRepository
{
    public function findAllHotel(string $query): array
    {
        return Hotel::find()
            ->select(['id', 'identificationNumber', 'voucherNumber'])
            ->where(['like', 'identificationNumber', $query])
            ->orWhere(['like', 'voucherNumber', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}