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

    public static function findOne(string $uid, ActiveRecord $model, array $withArray = []): ActiveRecord
    {
        $query = $model::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }
        return $query->where(['uid' => $uid])->one();
    }

    public static function find(array $query, ActiveRecord $model, array $withArray = []): ActiveRecord
    {
        $query = $model::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }
        return $query->where($query)->all();
    }

    public static function findAll(string $query): array
    {
        return Invoice::find()
            ->select(['id', 'eTicket', 'pnrCode'])
            ->where(['like', 'eTicket', $query])
            ->orWhere(['like', 'pnrCode', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}