<?php

namespace app\modules\account\repositories;

use app\repository\ParentRepository;
use Yii;
use yii\db\ActiveRecord;

class TransactionRepository extends ParentRepository
{
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