<?php

namespace app\modules\account\services;

use app\components\Helper;
use app\modules\account\models\Transaction;
use app\modules\account\repositories\TransactionRepository;
use yii\db\ActiveRecord;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository();
    }

    public function store(ActiveRecord $refObject, ActiveRecord $subRefObject,array $requestData): array
    {
        $transactionStatement = new Transaction();
        $transactionStatement->load(['Transaction' => $requestData]);
        $transactionStatement->transactionNumber = Helper::transactionNumber();
        $transactionStatement->refId = $refObject->id;
        $transactionStatement->refModel = $refObject::class;
        $transactionStatement->subRefId = $subRefObject->id;
        $transactionStatement->subRefModel = $subRefObject::class;
        if (!$transactionStatement->save()) {
            return ['error' => true, 'message' => Helper::processErrorMessages($transactionStatement->getErrors())];
        }
        return ['error' => false, 'data' => $transactionStatement, 'message' => 'Transaction created successfully'];
    }

}