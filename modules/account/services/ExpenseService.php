<?php

namespace app\modules\account\services;

use app\modules\account\repositories\ExpenseCategoryRepository;
use app\modules\account\repositories\ExpenseRepository;

class ExpenseService
{
    protected ExpenseRepository $expenseRepository;

    public function __construct()
    {
        $this->expenseRepository = new ExpenseRepository();
    }

    public function storeExpenseCategory($request, ExpenseCategory $category): ExpenseCategory
    {
        if (!isset($request['ExpenseCategory'])) {
            Yii::$app->session->setFlash('error', 'Expense Category data is required.');
            return $category;
        }

        if (!$category->load($request) || !$category->validate()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($category->getErrors()));
            return $category;
        }

        $category = $this->categoryRepository->store($category);
        if ($category->hasErrors()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($category->getErrors()));
            return $category;
        }

        Yii::$app->session->setFlash('error', 'Expense Category data is required.');
        return $category;
    }

    public function updateExpenseCategory($request, ExpenseCategory $category): ExpenseCategory
    {
        // Request Data
        if (empty($request['ExpenseCategory'])) {
            Yii::$app->session->setFlash('error', 'Expense Category data is required.');
            return $category;
        }

        if (!$category->load($request) || !$category->validate()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($category->getErrors()));
            return $category;
        }

        $category = $this->categoryRepository->update($category);
        if ($category->hasErrors()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($category->getErrors()));
            return $category;
        }

        Yii::$app->session->setFlash('success', 'Expense Category updated successfully.');
        return $category;
    }

    public function findCategory(string $uid): ExpenseCategory
    {
        return $this->categoryRepository->findOne($uid);
    }

    public function findCategories(string $query): array
    {
        $categories = $this->categoryRepository->findAll($query);
        $data = [];
        foreach ($categories as $category) {
            $data[] = ['id' => $category->id, 'text' => $category->name];
        }
        return ['results' => $data];
    }

    public function storeExpenseSubCategory($request, ExpenseSubCategory $category): ExpenseSubCategory
    {
        if (!isset($request['ExpenseSubCategory'])) {
            Yii::$app->session->setFlash('error', 'Expense Category data is required.');
            return $category;
        }

        if (!$category->load($request) || !$category->validate()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($category->getErrors()));
            return $category;
        }

        $category = $this->categoryRepository->store($category);
        if ($category->hasErrors()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($category->getErrors()));
            return $category;
        }

        Yii::$app->session->setFlash('error', 'Expense Category data is required.');
        return $category;
    }

    public function updateExpenseSubCategory($request, ExpenseCategory $category): ExpenseCategory
    {
        // Request Data
        if (empty($request['ExpenseCategory'])) {
            Yii::$app->session->setFlash('error', 'Expense Category data is required.');
            return $category;
        }

        if (!$category->load($request) || !$category->validate()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($category->getErrors()));
            return $category;
        }

        $category = $this->categoryRepository->update($category);
        if ($category->hasErrors()) {
            Yii::$app->session->setFlash('error', Utils::processErrorMessages($category->getErrors()));
            return $category;
        }

        Yii::$app->session->setFlash('success', 'Expense Category updated successfully.');
        return $category;
    }

    public function findSubCategory(string $uid): ExpenseSubCategory
    {
        return $this->subCategoryRepository->findOne($uid);
    }

    public function findSubCategoryBasedOnCategory(string $categoryId): array
    {
        $subCategories = $this->subCategoryRepository->getSubCategoriesBasedOnCategory($categoryId);
        $data = [];
        foreach ($subCategories as $category) {
            $data[] = ['id' => $category->id, 'name' => $category->name];
        }

        return $data;
    }

    public function storeExpense(array $request, ActiveRecord $expense): array
    {
        if (!isset($request['Expense'])) {
            throw new Exception('Expense data is required.');
        }
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!$expense->load($request) || !$expense->validate()) {
                throw new Exception('Expense validation failed - ' . Utils::processErrorMessages($expense->getErrors()));
            }

            // Store expense data
            $expense = $this->expenseRepository->store($expense);
            if ($expense->hasErrors()) {
                throw new Exception('Expense creation failed - ' . Utils::processErrorMessages($expense->getErrors()));
            }

            // Supplier Ledger process
            if (!empty($expense->supplierId)) {
                $ledgerRequestData = [
                    'title' => 'Expense',
                    'reference' => 'Expense Number - ' . $expense->name,
                    'refId' => $expense->supplierId,
                    'refModel' => Supplier::className(),
                    'subRefId' => $expense->id,
                    'subRefModel' => $expense::className(),
                    'debit' => 0,
                    'credit' => $expense->totalCost
                ];
                $ledgerRequestResponse = LedgerComponent::createNewLedger($ledgerRequestData);
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

}