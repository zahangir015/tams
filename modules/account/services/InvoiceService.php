<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Invoice;
use app\modules\account\models\InvoiceDetail;
use app\modules\account\models\RefundTransaction;
use app\modules\account\repositories\InvoiceRepository;
use app\modules\admin\models\User;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\services\SaleService;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class InvoiceService
{
    private InvoiceRepository $invoiceRepository;
    private TransactionService $transactionService;
    private LedgerService $ledgerService;
    private RefundTransactionService $refundTransactionService;

    public function __construct()
    {
        $this->invoiceRepository = new InvoiceRepository();
        $this->transactionService = new TransactionService();
        $this->refundTransactionService = new RefundTransactionService();
        $this->ledgerService = new LedgerService();
    }

    public function storeInvoice($requestData, ActiveRecord $invoice): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!array_key_exists('services', $requestData)) {
                throw new Exception('Service select is required.');
            }

            if (!$invoice->load(['Invoice' => $requestData['Invoice']])) {
                throw new Exception('Invoice loading failed.');
            }

            $totalDue = 0;
            $totalReceived = 0;
            $user = User::findOne(Yii::$app->user->id);

            // Invoice detail data process
            $serviceData = [];
            foreach ($requestData['services'] as $key => $service) {
                $serviceObj = json_decode($service);
                $totalDue += $serviceObj->dueAmount;
                $totalReceived += $serviceObj->paidAmount;
                $serviceData[$key]['refModel'] = $serviceObj->refModel;
                $serviceData[$key]['refId'] = $serviceObj->refId;
                $serviceData[$key]['subRefModel'] = Invoice::class;
                $serviceData[$key]['subRefId'] = $invoice->id;
                $serviceData[$key]['paidAmount'] = 0;
                $serviceData[$key]['dueAmount'] = $serviceObj->dueAmount;
            }

            // Invoice data process
            $invoice->dueAmount = $totalDue;
            $invoice->paidAmount = $totalReceived;
            $invoice->invoiceNumber = Utilities::invoiceNumber();
            $invoice = $this->invoiceRepository->store($invoice);
            if ($invoice->hasErrors()) {
                throw new Exception('Supplier Ledger creation failed - ' . Utilities::processErrorMessages($invoice->getErrors()));
            }

            //AttachmentFile::uploadsById($invoice, 'invoiceFile');
            // Service Data process
            $serviceDataProcessResponse = self::serviceDataProcessForInvoice($invoice, $serviceData, $user);
            if ($serviceDataProcessResponse['error']) {
                throw new Exception('Service Data process failed - ' . $serviceDataProcessResponse['message']);
            }

            // Customer Ledger process
            $ledgerRequestData = [
                'title' => 'Service Purchase',
                'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                'refId' => $invoice->customerId,
                'refModel' => Customer::class,
                'subRefId' => $invoice->id,
                'subRefModel' => $invoice::class,
                'debit' => $invoice->paidAmount,
                'credit' => 0
            ];
            $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
            if ($ledgerRequestResponse['error']) {
                throw new Exception('Customer Ledger creation failed - ' . $ledgerRequestResponse['message']);
            }

            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Invoice created successfully.');
            return true;
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function autoInvoice(int $customerId, array $services): array
    {
        $invoice = new Invoice();
        $invoice->date = date('Y-m-d');
        $invoice->expectedPaymentDate = $invoice->date;
        $invoice->customerId = $customerId;
        $invoice->invoiceNumber = Utilities::invoiceNumber();
        $invoice->dueAmount = array_sum(array_column($services, 'dueAmount'));;
        $invoice->paidAmount = 0;
        $invoice->remarks = 'Auto generated invoice';
        $invoice->discountedAmount = 0;
        $invoice->status = GlobalConstant::ACTIVE_STATUS;

        // Invoice data process
        $invoice = $this->invoiceRepository->store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice creation failed - ' . Utilities::processErrorMessages($invoice->getErrors())];
        }

        // Service process
        $serviceProcessResponse = self::serviceProcess($invoice, $services);
        if ($serviceProcessResponse['error']) {
            return ['error' => true, 'message' => $serviceProcessResponse['message']];
        }

        // Customer Ledger process
        $ledgerRequestData = [
            'title' => 'Service Purchase',
            'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
            'refId' => $invoice->customerId,
            'refModel' => Customer::class,
            'subRefId' => $invoice->id,
            'subRefModel' => $invoice::class,
            'debit' => array_sum(array_column($services, 'dueAmount')),
            'credit' => 0
        ];
        $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'message' => 'Invoice created successfully', 'data' => $invoice];
    }

    public function addRefundServiceToInvoice(ActiveRecord $newRefundService): array
    {
        $invoiceDetail = new InvoiceDetail();
        $invoiceDetail->invoiceId = $newRefundService->invoiceId;
        $invoiceDetail->dueAmount = ($newRefundService->quoteAmount - $newRefundService->receivedAmount);
        $invoiceDetail->paidAmount = $newRefundService->receivedAmount;
        $invoiceDetail->refId = $newRefundService->id;
        $invoiceDetail->refModel = $newRefundService::class;
        $invoiceDetail->status = GlobalConstant::ACTIVE_STATUS;
        $invoiceDetail = $this->invoiceRepository->store($invoiceDetail);
        if ($invoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice Detail creation failed - ' . Utilities::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetail = $this->invoiceRepository->findOne(['refId' => $newRefundService->motherTicketId, 'refModel' => $newRefundService::class], InvoiceDetail::class, []);
        $motherInvoiceDetail->status = 2;
        $motherInvoiceDetail = $this->invoiceRepository->store($motherInvoiceDetail);
        if ($motherInvoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed - ' . Utilities::processErrorMessages($motherInvoiceDetail->getErrors())];
        }

        // Invoice due update
        $invoice = $this->invoiceRepository->findOne(['id' => $newRefundService->invoiceId], Invoice::class, ['details']);
        $invoiceDetailArray = ArrayHelper::toArray($invoice->details);
        $invoice->dueAmount = (double)array_sum(array_column($invoiceDetailArray, 'dueAmount'));
        $invoice = $this->invoiceRepository->store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice due update failed - ' . Utilities::processErrorMessages($invoice->getErrors())];
        }

        // Customer Ledger process
        $ledgerRequestData = [
            'title' => 'Service Refund',
            'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
            'refId' => $invoice->customerId,
            'refModel' => Customer::class,
            'subRefId' => $invoice->id,
            'subRefModel' => Invoice::class,
            'debit' => ($invoiceDetail->dueAmount > 0) ? $invoiceDetail->dueAmount : 0,
            'credit' => ($invoiceDetail->dueAmount > 0) ? 0 : $invoiceDetail->dueAmount
        ];

        $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => $ledgerRequestResponse['error'], 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail];
    }

    public function autoInvoiceForRefund(Invoice $invoice, array $service, $user): array
    {
        // Invoice Details process
        $invoiceDetail = new InvoiceDetail();
        if ($invoiceDetail->load(['InvoiceDetail' => $service])) {
            if (!$invoiceDetail->save()) {
                return ['error' => true, 'message' => 'Invoice Detail create failed - ' . Utilities::processErrorMessages($invoiceDetail->getErrors())];
            }
        } else {
            return ['error' => true, 'message' => 'Invoice Detail loading failed - ' . Utilities::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetailUpdateResponse = InvoiceDetail::find()->where(['refId' => $service['motherId'], 'refModel' => $service['refModel']])->one();
        $motherInvoiceDetailUpdateResponse->status = GlobalConstant::REFUND_REQUESTED_STATUS;
        if (!$motherInvoiceDetailUpdateResponse->save()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed'];
        }

        // Invoice due update
        $invoiceDue = InvoiceDetail::find()
            ->select([new Expression('SUM(dueAmount) AS dueAmount')])
            ->where(['status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere(['invoiceId' => $invoiceDetail->invoiceId])
            ->asArray()->all();
        if (!$invoiceDue) {
            return ['status' => false, 'message' => 'Mother Invoice details update failed'];
        }

        $invoice->dueAmount = $invoiceDue[0]['dueAmount'];
        if (!$invoice->save()) {
            return ['error' => false, 'message' => 'Invoice due update failed - ' . Utilities::processErrorMessages($invoice->getErrors())];
        }

        // Customer Ledger process
        $ledgerRequestData = [
            'title' => 'Service Refund',
            'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
            'refId' => $invoice->customerId,
            'refModel' => Customer::class,
            'subRefId' => $invoice->id,
            'subRefModel' => $invoice::class,
            'debit' => ($invoiceDetail->dueAmount > 0) ? $invoiceDetail->dueAmount : 0,
            'credit' => ($invoiceDetail->dueAmount > 0) ? 0 : $invoiceDetail->dueAmount
        ];
        $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail];
    }

    public function autoInvoiceForReissue(Invoice $invoice, array $service, $user): array
    {
        // Invoice Details process
        $invoiceDetail = new InvoiceDetail();
        if ($invoiceDetail->load(['InvoiceDetail' => $service])) {
            if (!$invoiceDetail->save()) {
                return ['error' => true, 'message' => 'Invoice Detail create failed - ' . Utilities::processErrorMessages($invoiceDetail->getErrors())];
            }
        } else {
            return ['error' => true, 'message' => 'Invoice Detail loading failed - ' . Utilities::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        /*$motherInvoiceDetailUpdateResponse = InvoiceDetail::find()->where(['refId' => $service['motherId'], 'refModel' => $service['refModel']])->one();
        $motherInvoiceDetailUpdateResponse->status = GlobalConstant::REFUND_REQUESTED_STATUS;
        if (!$motherInvoiceDetailUpdateResponse->save()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed'];
        }*/

        // Invoice due update
        $invoiceDue = InvoiceDetail::find()
            ->select([new Expression('SUM(dueAmount) AS dueAmount')])
            ->where(['status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere(['invoiceId' => $invoiceDetail->invoiceId])
            ->asArray()->all();
        if (!$invoiceDue) {
            return ['status' => false, 'message' => 'Mother Invoice details update failed'];
        }

        $invoice->dueAmount = $invoiceDue[0]['dueAmount'];
        if (!$invoice->save()) {
            return ['error' => false, 'message' => 'Invoice due update failed - ' . Utilities::processErrorMessages($invoice->getErrors())];
        }

        // Customer Ledger process
        $ledgerRequestData = [
            'title' => 'Service Refund',
            'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
            'refId' => $invoice->customerId,
            'refModel' => Customer::class,
            'subRefId' => $invoice->id,
            'subRefModel' => $invoice::class,
            'debit' => ($invoiceDetail->dueAmount > 0) ? $invoiceDetail->dueAmount : 0,
            'credit' => ($invoiceDetail->dueAmount > 0) ? 0 : $invoiceDetail->dueAmount
        ];
        $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail];
    }

    private
    static function serviceProcess(Invoice $invoice, array $services): array
    {
        $invoiceDetailBatchData = [];
        $paymentTimelineBatchData = [];
        foreach ($services as $singleService) {
            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->invoiceId = $invoice->id;
            if (!$invoiceDetail->load(['InvoiceDetail' => $singleService]) || !$invoiceDetail->validate()) {
                return ['error' => true, 'message' => 'Invoice Details validation failed - ' . Utilities::processErrorMessages($invoiceDetail->getErrors())];
            }
            $invoiceDetailBatchData[] = $invoiceDetail->getAttributes();

            // Payment timeline process
            $processedData = PaymentTimelineService::processData($invoice, $singleService);
            $paymentTimelineBatchData = array_merge($paymentTimelineBatchData, $processedData);

            // service update
            $serviceObject = $singleService['refModel']::findOne(['id' => $singleService['refId']]);
            if (!$serviceObject) {
                return ['error' => true, 'message' => 'Service not found'];
            }
            $serviceObject->invoiceId = $invoice->id;
            if (!$serviceObject->update()) {
                return ['error' => true, 'message' => 'Service update failed - ' . Utilities::processErrorMessages($serviceObject->getErrors())];
            }
        }

        // Invoice Details insert process
        if (empty($invoiceDetailBatchData)) {
            return ['error' => true, 'message' => 'Invoice Detail Batch Data can not be empty.'];
        }
        if (!(new InvoiceRepository())->batchStore(InvoiceDetail::tableName(), array_keys($invoiceDetailBatchData[0]), $invoiceDetailBatchData)) {
            return ['error' => true, 'message' => 'Invoice Details batch insert failed'];
        }

        // Service Payment timeline batch insert
        $paymentTimelineProcessResponse = PaymentTimelineService::batchInsert($paymentTimelineBatchData);
        if ($paymentTimelineProcessResponse['error']) {
            return $paymentTimelineProcessResponse;
        }

        return ['error' => false, 'message' => 'Service process done.'];
    }

    public function addReissueServiceToInvoice(ActiveRecord $newReissueService): array
    {
        // Invoice detail process
        $invoiceDetail = new InvoiceDetail();
        $invoiceDetail->invoiceId = $newReissueService->invoiceId;
        $invoiceDetail->dueAmount = ($newReissueService->quoteAmount - $newReissueService->receivedAmount);
        $invoiceDetail->paidAmount = $newReissueService->receivedAmount;
        $invoiceDetail->refId = $newReissueService->id;
        $invoiceDetail->refModel = $newReissueService::class;
        $invoiceDetail->status = GlobalConstant::ACTIVE_STATUS;
        $invoiceDetail = $this->invoiceRepository->store($invoiceDetail);
        if ($invoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice Detail creation failed - ' . Utilities::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetail = $this->invoiceRepository->findOne(['refId' => $newReissueService->motherTicketId, 'refModel' => $newReissueService::class], InvoiceDetail::class, []);
        $motherInvoiceDetail->status = ServiceConstant::INVOICE_DETAIL_REISSUE_STATUS;
        $motherInvoiceDetail = $this->invoiceRepository->store($motherInvoiceDetail);
        if ($motherInvoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed - ' . Utilities::processErrorMessages($motherInvoiceDetail->getErrors())];
        }

        // Invoice due update
        $invoice = $this->invoiceRepository->findOne(['id' => $newReissueService->invoiceId], Invoice::class, []);
        $invoice->dueAmount += $invoiceDetail->dueAmount;
        $invoice = $this->invoiceRepository->store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice due update failed - ' . Utilities::processErrorMessages($invoice->getErrors())];
        }

        // Customer Ledger process
        $ledgerRequestData = [
            'title' => 'Service Reissue',
            'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
            'refId' => $invoice->customerId,
            'refModel' => Customer::class,
            'subRefId' => $invoice->id,
            'subRefModel' => Invoice::class,
            'debit' => ($invoiceDetail->dueAmount > 0) ? $invoiceDetail->dueAmount : 0,
            'credit' => ($invoiceDetail->dueAmount > 0) ? 0 : $invoiceDetail->dueAmount
        ];

        $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => $ledgerRequestResponse['error'], 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail->invoice];
    }

    public
    static function checkAndDetectPaymentStatus($due, $amount): string
    {
        $paymentStatus = NULL;
        $difference = abs(($due - $amount));
        if ($difference <= GlobalConstant::GRACE_DISCOUNT) {
            $paymentStatus = GlobalConstant::PAYMENT_STATUS['Full Paid'];
        } elseif ($amount > 0 && $due > 0) {
            $paymentStatus = GlobalConstant::PAYMENT_STATUS['Partially Paid'];
        } elseif ($amount == 0 && $due > 0) {
            $paymentStatus = GlobalConstant::PAYMENT_STATUS['Due'];
        }

        return $paymentStatus;
    }

    public function payment(ActiveRecord $invoice, array $requestData): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // If invoice is already paid
            if ($invoice->dueAmount <= 0) {
                throw  new Exception('You are not allowed to perform this action. This invoice is already paid.');
            }

            // If distribtion amount is greater than dueAmount
            if ($invoice->dueAmount < $requestData['Transaction']['paidAmount']) {
                throw  new Exception('You are not allowed to perform this action. This invoice will be over paid.');
            }

            $customer = $invoice->customer;
            // Process Transaction Data
            $transactionStatementStoreResponse = $this->transactionService->store($invoice, $customer, $requestData);
            if ($transactionStatementStoreResponse['error']) {
                throw new Exception('Transaction Statement Data process failed - ' . $transactionStatementStoreResponse['message']);
            }
            $transaction = $transactionStatementStoreResponse['data'];

            $distributionAmount = $transaction->paidAmount;
            $invoice->dueAmount -= $distributionAmount;
            $invoice->paidAmount += $distributionAmount;
            if (!$invoice->save()) {
                throw new Exception('Invoice update failed for payment - ' . Utilities::processErrorMessages($invoice->getErrors()));
            }

            // Refund status update
            if ($requestData['Transaction']['refundIds']) {
                $refundTransactions = RefundTransaction::find()->where(['id' => $requestData['Transaction']['refundIds']])->all();
                if (empty($refundTransactions)) {
                    throw new Exception('Refund Adjustment Failed');
                }

                // TODO check the refund adjustment calculation
                foreach ($refundTransactions as $key => $singleRefundTransaction) {
                    $singleRefundTransaction->adjustedAmount = $singleRefundTransaction->adjustmentAmount;
                    $singleRefundTransaction->isAdjusted = 1;
                    if (!$singleRefundTransaction->save()) {
                        throw new Exception('Refund Adjustment not save' . Utilities::processErrorMessages($singleRefundTransaction->getErrors()));
                    }
                }
            }

            //AttachmentFile::uploadsById($invoice, 'invoiceFile');
            // Amount distributions
            /*$invoiceDetails = InvoiceDetail::find()->select(['refId', 'refModel'])->where(['invoiceId' => $invoice->id])->all();
            if (!count($invoiceDetails)) {
                throw new Exception('No invoice details found');
            }
            $amountDistributionResponse = self::distributeAmountToServices($invoice, $distributionAmount);
            if (!count($invoiceDetails)) {
                throw new Exception('No invoice details found');
            }*/
            $amountDistributionResponse = self::distributePaidAmountToServices($invoice, $distributionAmount);
            if ($amountDistributionResponse['error']) {
                throw new Exception($amountDistributionResponse['message']);
            }

            // Customer Ledger process
            $customerLedgerRequestData = [
                'title' => 'Payment received',
                'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                'refId' => $invoice->customerId,
                'refModel' => Customer::class,
                'subRefId' => $invoice->id,
                'subRefModel' => $invoice::class,
                'debit' => 0,
                'credit' => $distributionAmount
            ];
            $customerLedgerRequestResponse = $this->ledgerService->store($customerLedgerRequestData);
            if ($customerLedgerRequestResponse['error']) {
                throw new Exception('Customer Ledger creation failed - ' . $customerLedgerRequestResponse['message']);
            }

            // Bank Ledger process
            $bankLedgerRequestData = [
                'title' => 'Service Payment received',
                'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                'refId' => $transaction->bankId,
                'refModel' => BankAccount::class,
                'subRefId' => $invoice->id,
                'subRefModel' => $invoice::class,
                'debit' => $distributionAmount,
                'credit' => 0
            ];
            $bankLedgerRequestResponse = $this->ledgerService->store($bankLedgerRequestData);
            if ($bankLedgerRequestResponse['error']) {
                throw new Exception('Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Invoice paid successfully.'];
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine()];
        }
    }

    private function distributePaidAmountToServices($invoice, $amount): array
    {
        foreach ($invoice->details as $invoiceDetail) {
            if ($amount <= 0) {
                break;
            }
            $service = $this->invoiceRepository->findOne(['id' => $invoiceDetail->refId], $invoiceDetail->refModel, []);
            if (!$service) {
                return ['error' => true, 'message' => "{$invoiceDetail->refModel} not found with id {$invoiceDetail->refId}"];
            }
            $due = $service->quoteAmount - $service->receivedAmount;
            if (($service->paymentStatus == ServiceConstant::PAYMENT_STATUS['Full Paid']) && ($due == 0)) {
                continue;
            }
            if ($due <= $amount) {
                $service->receivedAmount += $due;
                $service->paymentStatus = ServiceConstant::PAYMENT_STATUS['Full Paid'];
                //$paidAmountThisTime = $due;
                $amount -= $due;
            } else {
                $service->receivedAmount += $amount;
                $service->paymentStatus = ServiceConstant::PAYMENT_STATUS['Partially Paid'];
                //$paidAmountThisTime = $amount;
                $amount = 0;
            }
            $amountDue = $service->quoteAmount - $service->receivedAmount;
            if (!$service->save()) {
                return ['error' => true, 'message' => "{$invoiceDetail->refModel} not updated with id {$invoiceDetail->refId}"];
            }

            // Invoice detail update
            $invoiceDetail->dueAmount = $amountDue;
            $invoiceDetail->paidAmount = $service->receivedAmount;
            $invoiceDetail = $this->invoiceRepository->store($invoiceDetail);
            if ($invoiceDetail->hasErrors()) {
                return ['error' => true, 'message' => Utilities::processErrorMessages($invoiceDetail->getErrors())];
            }

            /*$servicePaymentDetailsStoreResponse = ServicePaymentDetail::storeServicePaymentDetail($invoiceDetail->refModel, $invoiceDetail->refId, Invoice::class, $service->invoiceId, $paidAmountThisTime, $amountDue, $user);
            if ($servicePaymentDetailsStoreResponse['error']) {
                return ['error' => true, 'message' => $servicePaymentDetailsStoreResponse['message']];
            }*/

        }

        return ['error' => false, 'message' => "Distribution has been made successfully"];
    }

    private
    static function serviceDataProcessForInvoice(ActiveRecord $invoice, array $services, mixed $user): array
    {
        $updatableServices = [];
        foreach ($services as $service) {
            // Invoice details entry
            $invoiceDetailResponse = (new InvoiceService)->storeOrUpdateInvoiceDetail($invoice, $service, $user);
            if ($invoiceDetailResponse['error']) {
                return $invoiceDetailResponse;
            }
            $updatableServices[] = [
                'refModel' => $service['refModel'],
                'query' => ['id' => $service['refId']],
                'data' => ['invoiceId' => $invoice->id]
            ];
        }

        if (!empty($updatableServices)) {
            $serviceUpdateResponse = SaleService::serviceUpdate($updatableServices);
            if ($serviceUpdateResponse['error']) {
                return $serviceUpdateResponse;
            }
        }

        return ['error' => false, 'message' => 'Service data processed successfully'];
    }

    protected function storeOrUpdateInvoiceDetail(ActiveRecord $invoice, $service, $user): array
    {
        $invoiceDetail = $this->invoiceRepository->findOne(['refModel' => $service['refModel'], 'refId' => $service['refId'], 'invoiceId' => $invoice->id], InvoiceDetail::class, []);
        if (!empty($invoiceDetail)) {
            $invoiceDetail->dueAmount = $service['dueAmount'];
            $invoiceDetail->paidAmount = $service['paidAmount'];
        } else {
            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->load(['InvoiceDetail' => $service]);
            $invoiceDetail->invoiceId = $invoice->id;
            $invoiceDetail->status = GlobalConstant::ACTIVE_STATUS;
        }

        $invoiceDetail = $this->invoiceRepository->store($invoiceDetail);
        if ($invoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice Details store failed - ' . Utilities::processErrorMessages($invoiceDetail->getErrors())];
        }

        return ['error' => false, 'message' => 'Success'];
    }

    public function getBankList(): array
    {
        return ArrayHelper::map(BankAccount::findAll(['status' => GlobalConstant::ACTIVE_STATUS]), 'id', 'name');
    }

    public static function updateInvoice(Invoice $invoice, array $services, $updateService = null): array
    {
        $totalInvoiceDueDifference = 0;
        // Invoice Detail update
        foreach ($services as $service) {
            // Invoice details finding
            $invoiceDetail = InvoiceDetail::find()->where(['invoiceId' => $invoice->id, 'refId' => $service['refId'], 'refModel' => $service['refModel']])->one();
            if (!$invoiceDetail) {
                return ['status' => false, 'message' => "Invoice Detail not found for sales Id: {$service['refId']} and sales: {$service['refModel']}"];
            }

            // Invoice Detail update
            if (!$invoiceDetail->load(['InvoiceDetail' => $service])) {
                return ['status' => false, 'message' => "Invoice Detail loading failed - " . Utilities::processErrorMessages($invoiceDetail->getErrors())];
            }
            $invoiceDetail = (new InvoiceRepository)->store($invoiceDetail);
            if ($invoiceDetail->hasErrors()) {
                return ['error' => false, 'message' => "Invoice Detail update failed - " . Utilities::processErrorMessages($invoiceDetail->getErrors())];
            }

            if ($updateService) {
                $paymentStatusResponse = self::checkAndDetectPaymentStatus($invoiceDetail->due, $invoiceDetail->amount);
                $updateAbleServiceArray[] = [
                    'refModel' => $invoiceDetail->refModel,
                    'query' => [
                        'id' => $invoiceDetail->refId,
                        'invoiceId' => $invoice->id
                    ],
                    'data' => [
                        'receivedAmount' => sprintf('%.2f', $invoiceDetail->amount),
                        'paymentStatus' => $paymentStatusResponse,
                        'updatedAt' => Utilities::convertToTimestamp(date('Y-m-d H:i:s')),
                        'updatedBy' => Yii::$app->user->id
                    ],
                ];
                $updateSaleResponse = SaleService::serviceUpdate($updateAbleServiceArray);
                if ($updateSaleResponse['error']) {
                    return ['error' => true, 'message' => "Service update failed - " . $updateSaleResponse['message']];
                }
            }
        }

        // Invoice due update
        $invoiceDue = InvoiceDetail::find()->select([new Expression('SUM(due) AS due')])->where(['status' => 1])->andWhere(['invoiceId' => $invoiceDetail->invoiceId])->asArray()->one();
        if (!$invoiceDue) {
            return ['error' => true, 'message' => 'Invoice due calculation failed.'];
        }
        $invoice->due = $invoiceDue['due'];
        $invoice = (new InvoiceRepository)->store($invoice);
        if (!$invoice->hasErrors()) {
            return ['error' => true, 'message' => "Invoice due update failed - " . Utilities::processErrorMessages($invoice->getErrors())];
        }

        return ['error' => false, 'message' => "Invoice due updated successfully", 'data' => $invoice];
    }

}