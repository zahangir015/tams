<?php

namespace app\modules\account\repositories;

use app\components\GlobalConstant;
use app\modules\account\models\Invoice;
use Yii;
use yii\db\ActiveRecord;

class InvoiceRepository
{
    public function store(ActiveRecord $object): ActiveRecord
    {
        $object->save();
        return $object;
    }

    public function batchStore($table, $columns, $rows): bool
    {
        if (Yii::$app->db->createCommand()->batchInsert($table, $columns, $rows)->execute()) {
            return true;
        }
        return false;
    }

    public function findOne(string $uid, array $withArray = []): ActiveRecord
    {
        $query = Invoice::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }
        return $query->where(['uid' => $uid])->one();
    }

    public function findAll(string $query): array
    {
        return Invoice::find()
            ->select(['id', 'eTicket', 'pnrCode'])
            ->where(['like', 'eTicket', $query])
            ->orWhere(['like', 'pnrCode', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}