<?php

namespace app\modules\account\services;

use app\components\Helper;
use app\components\Utils;
use app\modules\account\models\RefundTransaction;
use app\modules\account\models\RefundTransactionDetail;
use app\modules\account\models\Transaction;
use app\modules\account\repositories\RefundTransactionRepository;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\visa\Visa;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class RefundTransactionService
{
    public RefundTransactionRepository $refundTransactionRepository;

    public function __construct()
    {
        $this->refundTransactionRepository = new RefundTransactionRepository();
    }

    public function getRefundList($refModel, $refId): array
    {
        $refundTransactions = RefundTransaction::find()
            ->select([
                'refund_transaction.id',
                'refund_transaction.refId',
                'refund_transaction.refModel',
                'concat(identificationNumber," | ",totalAmount) as name',
                'refund_transaction.identificationNumber',
                'refund_transaction.totalAmount',
                /*'refund_transaction.payableAmount',
                'refund_transaction.receivableAmount',
                'refund_transaction.adjustedAmount'*/
            ])
            ->where(['like', 'refund_transaction.refModel', $refModel])
            ->andWhere(['refund_transaction.refId' => $refId])
            ->all();

        return ArrayHelper::map($refundTransactions, 'id', 'name');
    }

    public function customerPending(array $requestData): array
    {
        $html = '';
        $totalReceivable = 0;
        $totalPayable = 0;
        $customerId = $requestData['customerId'];
        if (empty($customerId)) {
            if (empty($html)) {
                $html .= "<tr> <td colspan='6' class='text-danger text-center'> Refund not found </td> </tr>";
            }
            return ['html' => $html, 'totalPayable' => $totalPayable, 'totalReceivable' => $totalReceivable];
        }

        $start_date = $end_date = null;
        if (isset($requestData['dateRange']) && strpos($requestData['dateRange'], '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $requestData['dateRange']);
        }

        $refundData = $this->refundTransactionRepository->customerPendingRefundServices($customerId, $start_date, $end_date);

        if (!empty($refundData)) {
            if (!empty($refundData['tickets'])) {
                $response = self::rowGenerator($refundData['tickets'], Ticket::class);
                $html .= $response['html'];
                $totalReceivable += $response['totalReceivable'];
                $totalPayable += $response['totalPayable'];
            }
            if (!empty($refundData['hotels'])) {
                $response = self::rowGenerator($refundData['hotels'], Hotel::class);
                $html .= $response['html'];
                $totalReceivable += $response['totalReceivable'];
                $totalPayable += $response['totalPayable'];
            }
            if (!empty($refundData['visas'])) {
                $response = self::rowGenerator($refundData['visas'], Visa::class);
                $html .= $response['html'];
                $totalReceivable += $response['totalReceivable'];
                $totalPayable += $response['totalPayable'];
            }
            if (!empty($refundData['holidays'])) {
                $response = self::rowGenerator($refundData['visas'], Holiday::class);
                $html .= $response['html'];
                $totalReceivable += $response['totalReceivable'];
                $totalPayable += $response['totalPayable'];
            }
        }
        if (empty($html)) {
            $html .= "<tr> <td colspan='6' class='text-danger text-center'> Refund not found </td> </tr>";
        }
        return ['html' => $html, 'totalPayable' => $totalPayable, 'totalReceivable' => $totalReceivable];

    }

    public static function rowGenerator($services, $serviceModel): array
    {
        $html = '';
        $receivable = 0;
        $payable = 0;
        $totalReceivable = 0;
        $totalPayable = 0;

        foreach ($services as $key => $service) {
            $amount = floatval($service['quoteAmount']) - floatval($service['receivedAmount']);
            if ($amount == 0) {
                continue;
            }
            if ($amount < 1) {
                $payable = abs($amount);
                $totalPayable += abs($amount);
            } else {
                $receivable = $amount;
                $totalReceivable += $amount;
            }

            $array = [
                'refId' => $service['id'],
                'refModel' => $serviceModel,
                'payable' => $payable,
                'receivable' => $receivable,
                'amount' => abs($amount),
                'quoteAmount' => $service['quoteAmount'],
            ];
            if ($serviceModel == Ticket::class){
                $identificationNumber = $service['eTicket'];
            }else{
                $identificationNumber = $service['identificationNumber'];
            }

            $html .= '<tr>';
            $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="RefundTransactionDetail[' . $key . ']" value="' . htmlspecialchars(json_encode($array)) . '"></td>';
            $html .= '<td><span class="badge bg-green">' . $identificationNumber . '</span></td>';
            $html .= '<td>' . ucfirst(Helper::getServiceName($serviceModel)) . ' <input type="text"  value="' . $array['refModel'] . '" hidden >  </td>';
            $html .= '<td>' . $service['issueDate'] . '</td>';
            $html .= '<td>' . $payable . '<input type="text" class="amount payable" id="payable' . $key . '"    value="' . $payable . '" hidden></td>';
            $html .= '<td>' . $receivable . '<input type="text" class="amount receivable" id="receivable' . $key . '"  value="' . $receivable . '" hidden></td>';
            $html .= '</tr>';
        }

        return ['html' => $html, 'totalPayable' => $totalPayable, 'totalReceivable' => $totalReceivable];
    }

    public function storeRefundTransaction(array $requestData, RefundTransaction $refundTransaction, Transaction $transaction)
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $refundTransaction->load($requestData);
            $refundTransaction->transactionNumber = RefundTransaction::setTransactionNumber();
            if (!$refundTransaction->save()) {
                throw new Exception(Helper::processErrorMessages($refundTransaction->getErrors()));
            }
            // store refund transaction detail
            $store = RefundTransactionDetail::storeData($request['RefundTransactionDetail'], $model);
            if (!$store['status']) {
                throw new Exception(Utils::processErrorMessages('not saved for Refund-transaction-detail ' . $store['message']));
            }
            // process customer ledger
            $ledgerRequestData = [
                'title' => 'Service Refund',
                'reference' => 'Refund Transaction - ' . $model->transactionNumber,
                'refId' => $model->refId,
                'refModel' => Customer::class,
                'subRefId' => $model->id,
                'subRefModel' => get_class($model),
                'debit' => $model->receivable,
                'credit' => $model->payable,// To customer
            ];

            $customerLedger = LedgerComponent::createNewLedger($ledgerRequestData);
            if ($customerLedger['error']) {
                throw new Exception('not saved for bank-ledger' . $customerLedger['message']);
            }
            if (!$request['RefundTransaction']['bankId']){
                throw new Exception('Bank is required');
            }
            // process bank ledger
            $bankLedgerRequestData = [
                'title' => 'Service Refund',
                'reference' => 'Refund Transaction - ' . $model->transactionNumber,
                'refId' => $request['RefundTransaction']['bankId'],
                'refModel' => BankAccount::class,
                'subRefId' => $model->id,
                'subRefModel' => get_class($model),
                'credit' => $model->receivable,
                'debit' => $model->payable,
            ];
            $bankLedger = LedgerComponent::createNewLedger($bankLedgerRequestData);
            if ($bankLedger['error']) {
                throw new Exception('not saved for bank-ledger' . $bankLedger['message']);
            }
            // form data for transaction-statement table
            $request['RefundTransaction']['transactionType'] = $model->payable == 0 ? 'Debit' : 'Credit';
            $requestData = TransactionStatementComponent::formDataForTransactionStatement($request['RefundTransaction'], $model->id, $model::className(), $request['customerId'], Customer::className(), $model->createdBy);
            $storeTransaction = TransactionStatementComponent::store($requestData);
            if ($storeTransaction['error']) {
                throw new Exception('Transaction not stored - ' . $storeTransaction['message']);
            }
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Refund Transaction for Customer saved successfully');

            return $this->redirect(['customer-refund-list']);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage(). ' at Line '. $e->getLine(). ' in file ' . $e->getFile());
            $transaction->rollBack();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

}