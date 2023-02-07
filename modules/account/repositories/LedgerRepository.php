<?php

namespace app\modules\account\repositories;

use app\components\GlobalConstant;
use app\modules\account\models\Invoice;
use app\modules\account\models\Ledger;
use app\repository\ParentRepository;
use Yii;
use yii\db\ActiveRecord;

class LedgerRepository extends ParentRepository
{
    public static function search(string $query): array
    {
        return Ledger::find()
            ->select(['id', 'eTicket', 'pnrCode'])
            ->where(['like', 'eTicket', $query])
            ->orWhere(['like', 'pnrCode', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }

    public function findLatestOne(int $refId, string $refModel)
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