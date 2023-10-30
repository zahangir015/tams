<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\Utils;
use app\modules\account\models\AdvancePayment;
use app\modules\account\models\BankAccount;
use app\modules\sale\models\Customer;
use Exception;
use Yii;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

class AccountService
{
    private TransactionService $transactionService;
    private LedgerService $ledgerService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
        $this->ledgerService = new LedgerService();
    }

    public function getBankList(): array
    {
        return ArrayHelper::map(BankAccount::findAll(['status' => GlobalConstant::ACTIVE_STATUS, 'agencyId' => Yii::$app->user->identity->agencyId]), 'id', 'name');
    }

    public function storeAdvancePayment(mixed $requestData, AdvancePayment $model)
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load($requestData)) {
                $model->paidAmount = $requestData['Transaction']['paidAmount'];
                $model->bankId = $requestData['Transaction']['bankId'];
                if (!$model->save()) {
                    $dbTransaction->rollBack();
                    return [
                        'error' => true,
                        'message' => 'Advance Payment loading failed - '.Utilities::processErrorMessages($model->getErrors())
                    ];
                }

                // Process Transaction Data
                $refDetail = $model->refModel::findOne(['id' => $model->refId]);
                $transactionStatementStoreResponse = $this->transactionService->store($model, $refDetail, $requestData);
                if ($transactionStatementStoreResponse['error']) {
                    $dbTransaction->rollBack();
                    return [
                        'error' => true,
                        'message' => 'Transaction Statement Data process failed - ' . $transactionStatementStoreResponse['message']
                    ];
                }

                // Reference Ledger process
                $ledgerRequestData = [
                    'title' => 'Advance Payment',
                    'reference' => 'Advance Payment Identification Number - ' . $model->identificationNumber,
                    'refId' => $model->refId,
                    'refModel' => $model->refModel,
                    'subRefId' => $model->id,
                    'subRefModel' => AdvancePayment::class,
                    'debit' => ($model->refModel == Customer::class) ? 0 : $model->paidAmount,
                    'credit' => ($model->refModel == Customer::class) ? $model->paidAmount : 0,
                ];
                $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
                if ($ledgerRequestResponse['error']) {
                    $dbTransaction->rollBack();
                    return [
                        'error' => true,
                        'message' => 'Ledger creation failed - ' . $ledgerRequestResponse['message']
                    ];
                }

                // Bank Ledger process
                $ledgerRequestData = [
                    'title' => 'Advance Payment',
                    'reference' => 'Advance Payment Identification Number - ' . $model->identificationNumber,
                    'refId' => $model->bankId,
                    'refModel' => BankAccount::class,
                    'subRefId' => $model->id,
                    'subRefModel' => AdvancePayment::class,
                    'debit' => ($model->refModel == Customer::class) ? $model->paidAmount : 0,
                    'credit' => ($model->refModel == Customer::class) ? 0: $model->paidAmount,
                ];
                $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
                if ($ledgerRequestResponse['error']) {
                    $dbTransaction->rollBack();
                    return [
                        'error' => true,
                        'message' => 'Bank Ledger creation failed - ' . $ledgerRequestResponse['message']
                    ];
                }

                $dbTransaction->commit();
                return [
                    'error' => false,
                    'message' => 'Advance Payment successfully.',
                    'data' => $model
                ];
            }else{
                return [
                    'error' => true,
                    'message' => 'Advance Payment loading failed - '.Utilities::processErrorMessages($model->getErrors())
                ];
            }
        } catch (Exception $exception) {
            return [
                'error' => true,
                'message' => 'Advance Payment loading failed - '.Utilities::processErrorMessages($model->getErrors())
            ];
        }
    }

}