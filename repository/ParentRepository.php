<?php

namespace app\repository;

use app\modules\hrm\models\EmployeeLeaveAllocation;
use Yii;
use yii\db\ActiveRecord;

class ParentRepository
{
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

    public function findOne(array $queryArray, $model, array $withArray = [], array $selectArray = []): ActiveRecord|null
    {
        $query = $model::find();

        if (!empty($selectArray)) {
            $query->select($selectArray);
        }

        if (!empty($withArray)) {
            $query->with($withArray);
        }

        return $query->where($queryArray)->one();
    }

    public function findAll($queryArray, $model, array $withArray = [], $asArray = false, array $selectArray = [])
    {
        $query = $model::find();
        if (!empty($selectArray)) {
            $query->select($selectArray);
        }

        if (!empty($withArray)) {
            $query->with($withArray);
        }

        $query->where($queryArray);
        if ($asArray) {
            $query->asArray();
        }

        return $query->all();
    }

    public function update(array $setArray, array $queryArray, $model): int
    {
        return $model::updateAll($setArray, $queryArray);
    }
}