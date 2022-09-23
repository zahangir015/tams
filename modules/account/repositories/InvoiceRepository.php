<?php

namespace app\modules\account\repositories;

use app\components\GlobalConstant;
use app\modules\account\models\Invoice;
use Yii;
use yii\db\ActiveRecord;

class InvoiceRepository
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

    public static function findOne(array $queryArray, string $model, array $withArray = [], $asArray = false): ActiveRecord
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

    public static function search(string $query): array
    {
        return Invoice::find()
            ->select(['id', 'eTicket', 'pnrCode'])
            ->where(['like', 'eTicket', $query])
            ->orWhere(['like', 'pnrCode', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}