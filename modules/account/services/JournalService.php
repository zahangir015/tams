<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\models\AccountGroup;
use app\modules\account\models\AccountType;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Journal;
use app\modules\account\models\JournalEntry;
use app\modules\account\models\Transaction;
use app\modules\account\repositories\JournalRepository;
use app\modules\sale\models\Supplier;
use Exception;
use Yii;
use yii\db\ActiveRecord;

class JournalService
{
    protected JournalRepository $journalRepository;
    protected LedgerService $ledgerService;
    protected TransactionService $transactionService;

    public function __construct()
    {
        $this->journalRepository = new JournalRepository();
        $this->ledgerService = new LedgerService();
        $this->transactionService = new TransactionService();
    }

    public function getAll(array $queryArray, string $model, array $withArray, bool $asArray): array
    {
        return $this->journalRepository->findAll($queryArray, $model, $withArray, $asArray);
    }

    public function storeJournal(array $requestData, Journal $journal): array
    {
        if (!isset($requestData['Journal'])) {
            return ['error' => true, 'message' => 'Journal data is required.'];
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $journal->load($requestData);
            if (!$journal->validate()) {
                throw new Exception('Journal validation failed - ' . Utilities::processErrorMessages($journal->getErrors()));
            }

            // Store journal data
            $journal = $this->journalRepository->store($journal);
            if ($journal->hasErrors()) {
                throw new Exception('Journal creation failed - ' . Utilities::processErrorMessages($journal->getErrors()));
            }

            // Journal Entry process
            $entries = array_filter($requestData['JournalEntry'], function ($v) {
                return array_filter($v) != array();
            });
            if (empty($entries)) {
                return ['error' => true, 'message' => 'Journal Entry data is required.'];
            }
            $totalEmpty = 0;
            foreach ($entries as $entry) {
                if (empty($entry['accountId'])) {
                    $totalEmpty++;
                    continue;
                }
                $journalEntryModel = new JournalEntry();
                $journalEntryModel->load(['JournalEntry' => $entry]);
                $journalEntryModel->journalId = $journal->id;
                $journalEntryModel = $this->journalRepository->store($journalEntryModel);
                if ($journalEntryModel->hasErrors()) {
                    throw new Exception('Journal Entry creation failed - ' . Utilities::processErrorMessages($journal->getErrors()));
                }

                // Ledger process
                $ledgerRequestData = [
                    'title' => 'Journal',
                    'reference' => 'Journal Number - ' . $journal->journalNumber,
                    'refId' => $journalEntryModel->refId,
                    'refModel' => $journalEntryModel->refModel,
                    'subRefId' => $journalEntryModel->journalId,
                    'subRefModel' => Journal::class,
                    'debit' => $journalEntryModel->debit,
                    'credit' => $journalEntryModel->credit
                ];
                $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
                if ($ledgerRequestResponse['error']) {
                    throw new Exception('Ledger creation failed - ' . $ledgerRequestResponse['message']);
                }
            }

            if (count($entries) == $totalEmpty) {
                throw new Exception('Journal Entry - Chart of Account is required');
            }


            // Transaction Process
            /*$transactionData = TransactionService::formDataForTransactionStatement($request['TransactionStatement'], $journal->id, Journal::className(), $journal->companyId, Company::className(), Yii::$app->user->id);
            $transactionStoreResponse = TransactionService::store($transactionData);
            if ($transactionStoreResponse['error']) {
                throw new Exception('Transaction Statement creation failed - ' . $transactionStoreResponse['message']);
            }
            $journal->totalPaid += $transactionStoreResponse['data']->amount;
            $journal = $this->journalRepository->update($journal);*/

            // Bank Ledger process
            /*$bankLedgerRequestData = [
                'title' => 'Journal',
                'reference' => 'Journal Number - ' . $journal->name,
                'refId' => $transactionStoreResponse['data']->bankId,
                'refModel' => BankAccount::className(),
                'subRefId' => $journal->id,
                'subRefModel' => $journal::className(),
                'debit' => 0,
                'credit' => $transactionStoreResponse['data']->amount
            ];
            $bankLedgerRequestResponse = LedgerComponent::createNewLedger($bankLedgerRequestData);
            if ($bankLedgerRequestResponse['error']) {
                throw new Exception('Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
            }*/

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Journal is successfully created.', 'data' => $journal];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage() . $e->getFile() . $e->getLine()];
        }
    }

    public function updateJournal($request, Journal $journal): array
    {
        // Request Data
        if (empty($request['Journal'])) {
            return ['error' => true, 'message' => 'Journal data is required.'];
        }
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!$journal->load($request) || !$journal->validate()) {
                throw new Exception('Journal validation failed - ' . Utilities::processErrorMessages($journal->getErrors()));
            }

            $journal = $this->journalRepository->update($journal);
            if ($journal->hasErrors()) {
                Yii::$app->session->setFlash('error', Utilities::processErrorMessages($journal->getErrors()));
                return $journal;
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Journal is successfully created.', 'data' => $journal];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function payJournal(array $requestData, Journal $journal, Transaction $transaction): array
    {
        if (!isset($requestData['Transaction'])) {
            return ['error' => true, 'message' => 'Transaction Statement data is required.'];
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if ($requestData['Transaction']['paidAmount'] > ($journal->totalCost - $journal->totalPaid)) {
                return ['error' => true, 'message' => 'Extra amount could not be paid.'];
            }
            // Process Transaction Data
            $transactionStatementStoreResponse = $this->transactionService->store($journal, $journal->supplier, $requestData);
            if ($transactionStatementStoreResponse['error']) {
                throw new Exception('Transaction Statement Data process failed - ' . $transactionStatementStoreResponse['message']);
            }
            $transaction = $transactionStatementStoreResponse['data'];

            // Journal update
            $journal->totalPaid += $transaction->paidAmount;
            $journal->paymentStatus = ($journal->totalCost == $journal->totalPaid) ? GlobalConstant::PAYMENT_STATUS['Full Paid'] : GlobalConstant::PAYMENT_STATUS['Partially Paid'];
            $journal = $this->journalRepository->store($journal);

            // Supplier Ledger process for payment
            $supplierLedgerRequestData = [
                'title' => 'Journal',
                'reference' => 'Journal Number - ' . $journal->identificationNumber,
                'refId' => $journal->supplierId,
                'refModel' => Supplier::class,
                'subRefId' => $journal->id,
                'subRefModel' => $journal::class,
                'debit' => $transaction->paidAmount,
                'credit' => 0
            ];
            $ledgerRequestResponse = $this->ledgerService->store($supplierLedgerRequestData);
            if ($ledgerRequestResponse['error']) {
                throw new Exception('Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
            }


            // Bank Ledger process
            $bankLedgerRequestData = [
                'title' => 'Journal',
                'reference' => 'Journal Number - ' . $journal->identificationNumber,
                'refId' => $transaction->bankId,
                'refModel' => BankAccount::class,
                'subRefId' => $journal->id,
                'subRefModel' => $journal::class,
                'debit' => 0,
                'credit' => $transaction->paidAmount
            ];
            $bankLedgerRequestResponse = $this->ledgerService->store($bankLedgerRequestData);
            if ($bankLedgerRequestResponse['error']) {
                throw new Exception('Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Journal payment done successfully.', 'data' => $journal];

        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function findJournal(string $uid): array|ActiveRecord|null
    {
        if (($model = $this->journalRepository->findOne($uid)) !== null) {
            return $model;
        } else {
            Yii::$app->session->setFlash('error', 'Journal not found.');
            return null;
        }
    }

}