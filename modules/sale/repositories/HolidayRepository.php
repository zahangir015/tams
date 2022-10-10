<?php

namespace app\modules\sale\repositories;

use app\modules\sale\models\holiday\Holiday;

class HolidayRepository
{
    public function findOne(string $uid, mixed $withArray): array|\yii\db\ActiveRecord|null
    {
        $query = Holiday::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }

        return $query->where(['uid' => $uid])->one();
    }
}