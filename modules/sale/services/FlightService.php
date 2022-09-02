<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\admin\models\User;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Supplier;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\repositories\FlightRepository;
use Yii;
use yii\db\Exception;

class FlightService
{
    private FlightRepository $flightRepository;

    public function __construct()
    {
        $this->flightRepository = new FlightRepository();
    }

    public function storeTicket($requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Ticket']) && !empty($requestData['TicketSupplier'])) {
                $error = false;
                $tickets = [];
                $supplierLedgerArray = [];
                $autoInvoiceCreateResponse = null;
                $customer = Customer::findOne(['id' => $requestData['Ticket'][0]['customerId']]);


                foreach ($requestData['Ticket'] as $key => $ticketData) {
                    $ticket = new Ticket();
                    $ticket->scenario = 'create';
                    $supplier = Supplier::findOne(['id' => $requestData['TicketSupplier'][$key]['supplierId']]);

                    if ($ticket->load(['Ticket' => $ticketData])) {
                        $ticket = $this->flightRepository->store($ticket);
                        if ($ticket->hasErrors()) {
                            throw new Exception('Ticket create failed - ' . Helper::processErrorMessages($ticket->getErrors()));
                        }

                        // Ticket Supplier data process
                        $ticketSupplier = new TicketSupplier();
                        $ticketSupplier->load(['TicketSupplier' => $ticket->getAttributes(['issueDate', 'eTicket', 'pnrCode', 'airlineId', 'paymentStatus', 'type', 'costOfSale', 'baseFare', 'tax'])]);
                        $ticketSupplier->load(['TicketSupplier' => $requestData['TicketSupplier'][$key]]);
                        $ticketSupplier->ticketId = $ticket->id;
                        $ticketSupplier = $this->flightRepository->store($ticketSupplier);
                        if ($ticketSupplier->hasErrors()) {
                            throw new Exception('Ticket Supplier create failed - ' . Helper::processErrorMessages($ticketSupplier->getErrors()));
                        }

                        // Invoice details data process
                        if (isset($requestData['invoice'])) {
                            $tickets[] = [
                                'refId' => $ticket->id,
                                'refModel' => Ticket::class,
                                'due' => $ticket->quoteAmount,
                                'amount' => 0,
                                'supplierData' => [
                                    [
                                        'refId' => $ticketSupplier->id,
                                        'refModel' => $ticketSupplier::class,
                                        'subRefId' => null,
                                        'subRefModel' => Invoice::className(),
                                        'due' => $ticketSupplier->costOfSale,
                                        'amount' => $ticketSupplier->paidAmount,
                                    ]
                                ]
                            ];
                        }

                        // Supplier ledger data process
                        if (isset($supplierLedgerArray[$ticketSupplier->supplier->id])) {
                            $supplierLedgerArray[$ticketSupplier->supplier->id]['credit'] += $ticketSupplier->costOfSale;
                        } else {
                            $supplierLedgerArray[$ticketSupplier->supplier->id] = [
                                'debit' => 0,
                                'credit' => $ticketSupplier->costOfSale,
                                'subRefId' => null
                            ];
                        }

                    } else {
                        throw new Exception('Ticket data loading failed - ' . Helper::processErrorMessages($ticket->getErrors()));
                    }
                }

                // Invoice process and create
                $autoInvoiceCreateResponse = InvoiceService::autoInvoice($customer->id, $tickets, $requestData['group'],Yii::$app->user);
                if ($autoInvoiceCreateResponse['error']) {
                    $dbTransaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Invoice - ' . $autoInvoiceCreateResponse['message']);
                    return false;
                }

                // Ledger process
                foreach ($supplierLedgerArray as $key => $value) {
                    $ledgerRequestData = [
                        'title' => 'Service Purchase',
                        'reference' => 'Service Purchase',
                        'refId' => $key,
                        'refModel' => Supplier::className(),
                        'subRefId' => ($value['subRefId']) ?? $autoInvoiceCreateResponse['data']->id,
                        'subRefModel' => Invoice::className(),
                        'debit' => $value['debit'],
                        'credit' => $value['credit']
                    ];
                    $ledgerRequestResponse = LedgerComponent::createNewLedger($ledgerRequestData);
                    if ($ledgerRequestResponse['error']) {
                        $dbTransaction->rollBack();
                        Yii::$app->session->setFlash('success', 'Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                        return false;
                    }
                }

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Ticket added successfully');
                return true;
            }
            return false;
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
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

        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $expense;
        }
    }

    public function findExpense(string $uid): \yii\db\ActiveRecord
    {
        return $this->expenseRepository->findOne($uid);
    }


}