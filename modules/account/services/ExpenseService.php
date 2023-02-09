<?php

namespace app\modules\account\services;

use app\components\Helper;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Expense;
use app\modules\account\models\ExpenseSubCategory;
use app\modules\account\models\Transaction;
use app\modules\account\repositories\ExpenseRepository;
use app\modules\sale\models\Supplier;
use Exception;
use Yii;

class ExpenseService
{
    protected ExpenseRepository $expenseRepository;
    protected LedgerService $ledgerService;
    protected TransactionService $transactionService;

    public function __construct()
    {
        $this->expenseRepository = new ExpenseRepository();
        $this->ledgerService = new LedgerService();
        $this->transactionService = new TransactionService();
    }

    public function getAll(array $queryArray, string $model, array $withArray, bool $asArray)
    {
        return $this->expenseRepository->findAll($queryArray, $model, $withArray, $asArray);
    }

    public function storeExpense(array $request, Expense $expense): array
    {
        if (!isset($request['Expense'])) {
            return ['error' => true, 'message' => 'Expense data is required.'];
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $expense->load($request);
            $expense->identificationNumber = Helper::expenseIdentificationNumber();
            if (!$expense->validate()) {
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
                    'reference' => 'Expense Number - ' . $expense->identificationNumber,
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
            /*$transactionData = TransactionService::formDataForTransactionStatement($request['TransactionStatement'], $expense->id, Expense::className(), $expense->companyId, Company::className(), Yii::$app->user->id);
            $transactionStoreResponse = TransactionService::store($transactionData);
            if ($transactionStoreResponse['error']) {
                throw new Exception('Transaction Statement creation failed - ' . $transactionStoreResponse['message']);
            }
            $expense->totalPaid += $transactionStoreResponse['data']->amount;
            $expense = $this->expenseRepository->update($expense);*/

            // Bank Ledger process
            /*$bankLedgerRequestData = [
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
            }*/

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Expense is successfully created.', 'data' => $expense];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage() . $e->getFile() . $e->getLine()];
        }
    }

    public function updateExpense($request, Expense $expense): array
    {
        // Request Data
        if (empty($request['Expense'])) {
            return ['error' => true, 'message' => 'Expense data is required.'];
        }
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!$expense->load($request) || !$expense->validate()) {
                throw new Exception('Expense validation failed - ' . Helper::processErrorMessages($expense->getErrors()));
            }

            $expense = $this->expenseRepository->update($expense);
            if ($expense->hasErrors()) {
                Yii::$app->session->setFlash('error', Helper::processErrorMessages($expense->getErrors()));
                return $expense;
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Expense is successfully created.', 'data' => $expense];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function payExpense(array $requestData, Expense $expense, Transaction $transaction): array
    {
        if (!isset($requestData['Transaction'])) {
            return ['error' => true, 'message' => 'Transaction Statement data is required.'];
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if ($requestData['Transaction']['paidAmount'] > ($expense->totalCost - $expense->totalPaid)) {
                return ['error' => true, 'message' => 'Extra amount could not be paid.'];
            }
            // Process Transaction Data
            $transactionStatementStoreResponse = $this->transactionService->store($expense, $expense->supplier, $requestData);
            if ($transactionStatementStoreResponse['error']) {
                throw new Exception('Transaction Statement Data process failed - ' . $transactionStatementStoreResponse['message']);
            }
            $transaction = $transactionStatementStoreResponse['data'];

            // Expense update
            $expense->totalPaid += $transaction->paidAmount;
            $expense = $this->expenseRepository->store($expense);

            // Supplier Ledger process for payment
            $supplierLedgerRequestData = [
                'title' => 'Expense',
                'reference' => 'Expense Number - ' . $expense->identificationNumber,
                'refId' => $expense->supplierId,
                'refModel' => Supplier::class,
                'subRefId' => $expense->id,
                'subRefModel' => $expense::class,
                'debit' => $transaction->paidAmount,
                'credit' => 0
            ];
            $ledgerRequestResponse = $this->ledgerService->store($supplierLedgerRequestData);
            if ($ledgerRequestResponse['error']) {
                throw new Exception('Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
            }


            // Bank Ledger process
            $bankLedgerRequestData = [
                'title' => 'Expense',
                'reference' => 'Expense Number - ' . $expense->identificationNumber,
                'refId' => $transaction->bankId,
                'refModel' => BankAccount::class,
                'subRefId' => $expense->id,
                'subRefModel' => $expense::class,
                'debit' => 0,
                'credit' => $transaction->paidAmount
            ];
            $bankLedgerRequestResponse = $this->ledgerService->store($bankLedgerRequestData);
            if ($bankLedgerRequestResponse['error']) {
                throw new Exception('Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Expense payment done successfully.', 'data' => $expense];

        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
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