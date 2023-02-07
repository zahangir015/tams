<?php

namespace app\modules\account\repositories;

use app\components\GlobalConstant;
use app\modules\account\models\Invoice;
use app\repository\ParentRepository;

class InvoiceRepository extends ParentRepository
{
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