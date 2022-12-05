<?php
namespace app\repository;

use Yii;
use yii\db\ActiveRecord;

class ParentRepository
{
    public static function batchStore($table, $columns, $rows): bool
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

    public function findOne(array $queryArray, $model, array $withArray): ActiveRecord
    {
        $query = $model::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }

        return $query->where($queryArray)->one();
    }
}