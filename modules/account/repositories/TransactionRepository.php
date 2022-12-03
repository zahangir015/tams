<?php

namespace app\modules\account\repositories;

use Yii;
use yii\db\ActiveRecord;

class TransactionRepository
{
    public static function store(ActiveRecord $object): ActiveRecord
    {
        $object->save();
        return $object;
    }

    public static function batchStore($table, $columns, $rows): bool
    {
        if (Yii::$app->db->createCommand()->batchInsert($table, $columns, $rows)->execute()) {
            return true;
        }
        return false;
    }

    public static function findOne(array $queryArray, string $model, array $withArray = [], $asArray = false): array|ActiveRecord|null
    {
        $query = $model::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }

        $query->where($queryArray);
        if ($asArray){
            $query->asArray();
        }

        return $query->one();
    }

    public static function findAll(array $queryArray, string $model, array $withArray = [], $asArray = false): array
    {
        $query = $model::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }

        $query->where($queryArray);
        if ($asArray){
            $query->asArray();
        }

        return $query->all();
    }
}