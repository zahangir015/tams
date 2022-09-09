<?php

namespace app\modules\account\repositories;

use app\components\GlobalConstant;
use app\modules\account\models\Invoice;
use app\modules\account\models\Ledger;
use Yii;
use yii\db\ActiveRecord;

class LedgerRepository
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

    public static function findOne(string $uid, array $withArray = []): ActiveRecord
    {
        $query = Ledger::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }
        return $query->where(['uid' => $uid])->one();
    }

    public static function findAll(string $query): array
    {
        return Ledger::find()
            ->select(['id', 'eTicket', 'pnrCode'])
            ->where(['like', 'eTicket', $query])
            ->orWhere(['like', 'pnrCode', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }

    public static function findLatestOne(int $refId, string $refModel)
    {
        return Ledger::find()
            ->where(['refId' => $refId, 'refModel' => $refModel])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->one();
    }

    public static function getPrevLedgerBalance(int $refId, string $refModel, int $ledgerId)
    {
        return Ledger::find()
            ->where(['refId' => $refId, 'refModel' => $refModel])
            ->andWhere(['<', 'id', $ledgerId])
            ->orderBy(['id' => SORT_ASC])
            ->one()->balance ?? 0;
    }
}