<?php

namespace app\modules\account\services;

class LedgerService
{
    public function storeLedger(array $requestData): array
    {

    }

    public static function createNewLedger(array $requestData): array
    {
        $model = new  Ledger();
        $balance = 0;

        // Find Last ledger
        $ledger = self::getLastRecordOfClient($requestData['refId'], $requestData['refModel']); // bank/customer/supplier

        if ($ledger) {
            $balance = $ledger['balance'];
        }

        // Process Ledger data
        $model->load(['Ledger' => $requestData]);

        $closingBalance = self::calculateClosingBalance($requestData['refModel'], $model, $balance);

        $model->balance = $closingBalance;
        $model->date = date('Y-m-d');
        $model->status = Constant::ACTIVE_STATUS;
        $model->createdBy = 1;
        if (!$model->save()) {
            return ['error' => true, 'message' => Utils::processErrorMessages($model->getErrors())];
        }
        return ['error' => false, 'data' => $model];
    }

    public static function getLastRecordOfClient(int $refId, string $refModel)
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