<?php

namespace app\modules\sale\repositories;

use app\modules\sale\models\holiday\Holiday;
use yii\db\ActiveRecord;

class HolidayRepository
{
    public function findOne(string $uid, mixed $withArray): array|ActiveRecord|null
    {
        $query = Holiday::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }

        return $query->where(['uid' => $uid])->one();
    }

    public function batchStore($table, $columns, $rows): bool
    {
        if (Yii::$app->db->createCommand()->batchInsert($table, $columns, $rows)->execute()) {
            return true;
        }
        return false;
    }

    public function store(ActiveRecord $object): ActiveRecord
    {
        $object->save();
        return $object;
    }
}