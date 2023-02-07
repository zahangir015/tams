<?php

namespace app\modules\account\services;

use app\components\Helper;
use app\modules\account\models\Expense;
use app\modules\account\models\ExpenseSubCategory;
use app\modules\account\repositories\ExpenseRepository;
use app\modules\sale\models\Supplier;
use Exception;
use Yii;

class ExpenseService
{
    protected ExpenseRepository $expenseRepository;
    protected LedgerService $ledgerService;

    public function __construct()
    {
        $this->expenseRepository = new ExpenseRepository();
        $this->ledgerService = new LedgerService();
    }

    public function getAll(array $queryArray, string $model, array $withArray, bool $asArray)
    {
        return $this->expenseRepository->findAll($queryArray, $model, $withArray, $asArray);
    }

    public function storeExpense(array $request, Expense $expense): array
    {
        if (!isset($request['Expense'])) {
            throw new Exception('Expense data is required.');
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!$expense->load($request) || !$expense->validate()) {
                throw new Exception('Expense validation failed - ' . Helper::processErrorMessages($expense->getErrors()));
            }

            // Store expense data
            $expense = $this->expenseRepository->store($expense);
            if ($expense->hasErrors()) {
                throw new Exception('Expense creation failed - ' . Helper::processErrorMessages($expense->getErrors()));
            }

            // Supplier Ledger process
            if (!empty($expense->supplierId)) {
                $ledgerRequestData = [
                    'title' => 'Expense',
                    'reference' => 'Expense Number - ' . $expense->name,
                    'refId' => $expense->supplierId,
                    'refModel' => Supplier::class,
                    'subRefId' => $expense->id,
                    'subRefModel' => Expense::class,
                    'debit' => 0,
                    'credit' => $expense->totalCost
                ];
                $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
                if ($ledgerRequestResponse['error']) {
                    throw new Exception('Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                }
            }

            // Transaction Process
            $transactionData = TransactionService::formDataForTransactionStatement($request['TransactionStatement'], $expense->id, Expense::className(), $expense->companyId, Company::className(), Yii::$app->user->id);
            $transactionStoreResponse = TransactionService::store($transactionData);
            if ($transactionStoreResponse['error']) {
                throw new Exception('Transaction Statement creation failed - ' . $transactionStoreResponse['message']);
            }

            //
            $expense->totalPaid += $transactionStoreResponse['data']->amount;
            $expense = $this->expenseRepository->update($expense);

            // Bank Ledger process
            $bankLedgerRequestData = [
                'title' => 'Expense',
                'reference' => 'Expense Number - ' . $expense->name,
                'refId' => $transactionStoreResponse['data']->bankId,
                'refModel' => BankAccount::className(),
                'subRefId' => $expense->id,
                'subRefModel' => $expense::className(),
                'debit' => 0,
                'credit' => $transactionStoreResponse['data']->amount
            ];
            $bankLedgerRequestResponse = LedgerComponent::createNewLedger($bankLedgerRequestData);
            if ($bankLedgerRequestResponse['error']) {
                throw new Exception('Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
            }

            $dbTransaction->commit();
            return ['error' => false, 'Expense is successfully created.', 'data' => $expense];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, $e->getMessage()];
        }
    }

    public function updateExpense($request, Expense $expense): Expense
    {
        // Request Data
        if (empty($request['Expense'])) {
            Yii::$app->session->setFlash('error', 'Expense data is required.');
            return $expense;
        }

        if (!$expense->load($request) || !$expense->validate()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($expense->getErrors()));
            return $expense;
        }

        $expense = $this->expenseRepository->update($expense);
        if ($expense->hasErrors()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($expense->getErrors()));
            return $expense;
        }

        Yii::$app->session->setFlash('success', 'Expense updated successfully.');
        return $expense;
    }

    public function payExpense($request, Expense $expense): Expense
    {
        if (!isset($request['TransactionStatement'])) {
            Yii::$app->session->setFlash('error', 'Transaction Statement data is required.');
            return $expense;
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Transaction Process
            $transactionData = TransactionService::formDataForTransactionStatement($request['TransactionStatement'], $expense->id, Expense::className(), $expense->supplierId, Supplier::className(), Yii::$app->user->id);
            $transactionStoreResponse = TransactionService::store($transactionData);
            if ($transactionStoreResponse['error']) {
                Yii::$app->session->setFlash('error', 'Transaction Statement - ' . $transactionStoreResponse['message']);
                $dbTransaction->rollBack();
                return $expense;
            }
            //
            $expense->totalPaid += $transactionStoreResponse['data']->amount;
            $expense = $this->expenseRepository->update($expense);

            // Supplier Ledger process for payment
            $supplierLedgerRequestData = [
                'title' => 'Expense',
                'reference' => 'Expense Number - ' . $expense->name,
                'refId' => $expense->supplierId,
                'refModel' => Supplier::className(),
                'subRefId' => $expense->id,
                'subRefModel' => $expense::className(),
                'debit' => $transactionStoreResponse['data']->amount,
                'credit' => 0
            ];
            $ledgerRequestResponse = LedgerComponent::createNewLedger($supplierLedgerRequestData);
            if ($ledgerRequestResponse['error']) {
                Yii::$app->session->setFlash('error', 'Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                $dbTransaction->rollBack();
                return $expense;
            }


            // Bank Ledger process
            $bankLedgerRequestData = [
                'title' => 'Expense',
                'reference' => 'Expense Number - ' . $expense->name,
                'refId' => $transactionStoreResponse['data']->bankId,
                'refModel' => BankAccount::className(),
                'subRefId' => $expense->id,
                'subRefModel' => $expense::className(),
                'debit' => 0,
                'credit' => $transactionStoreResponse['data']->amount
            ];
            $bankLedgerRequestResponse = LedgerComponent::createNewLedger($bankLedgerRequestData);
            if ($bankLedgerRequestResponse['error']) {
                Yii::$app->session->setFlash('error', 'Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
                $dbTransaction->rollBack();
                return $expense;
            }

            Yii::$app->session->setFlash('success', 'Expense payment done successfully.');
            $dbTransaction->commit();
            return $expense;

        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $expense;
        }
    }

    public function findExpense(string $uid)
    {
        if (($model = $this->expenseRepository->findOne($uid)) !== null) {
            return $model;
        } else {
            Yii::$app->session->setFlash('error', 'Expense not found.');
            return null;
        }
    }

    public function getSubCategoryList(array $queryArray): array
    {
        $subCategoryList = self::getAll($queryArray, ExpenseSubCategory::class, [], true);
        $subCategoryDataArray = [];
        foreach ($subCategoryList as $value) {
            $subCategoryDataArray[] = ['id' => $value['id'], 'name' => $value['name']];
        }

        return $subCategoryDataArray;
    }

}