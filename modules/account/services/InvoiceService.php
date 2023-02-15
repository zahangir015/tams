<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Helper;
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
            $invoice->invoiceNumber = Helper::invoiceNumber();
            $invoice = $this->invoiceRepository->store($invoice);
            if ($invoice->hasErrors()) {
                throw new Exception('Supplier Ledger creation failed - ' . Helper::processErrorMessages($invoice->getErrors()));
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
        $invoice->invoiceNumber = Helper::invoiceNumber();
        $invoice->dueAmount = array_sum(array_column($services, 'dueAmount'));;
        $invoice->paidAmount = 0;
        $invoice->remarks = 'Auto generated invoice';
        $invoice->discountedAmount = 0;
        $invoice->status = GlobalConstant::ACTIVE_STATUS;

        // Invoice data process
        $invoice = $this->invoiceRepository->store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice creation failed - ' . Helper::processErrorMessages($invoice->getErrors())];
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
            return ['error' => true, 'message' => 'Invoice Detail creation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetail = $this->invoiceRepository->findOne(['refId' => $newRefundService->motherTicketId, 'refModel' => $newRefundService::class], InvoiceDetail::class, []);
        $motherInvoiceDetail->status = 2;
        $motherInvoiceDetail = $this->invoiceRepository->store($motherInvoiceDetail);
        if ($motherInvoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed - ' . Helper::processErrorMessages($motherInvoiceDetail->getErrors())];
        }

        // Invoice due update
        $invoice = $this->invoiceRepository->findOne(['id' => $newRefundService->invoiceId], Invoice::class, ['details']);
        $invoiceDetailArray = ArrayHelper::toArray($invoice->details);
        $invoice->dueAmount = (double)array_sum(array_column($invoiceDetailArray, 'dueAmount'));
        $invoice = $this->invoiceRepository->store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice due update failed - ' . Helper::processErrorMessages($invoice->getErrors())];
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
                return ['error' => true, 'message' => 'Invoice Detail create failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
            }
        } else {
            return ['error' => true, 'message' => 'Invoice Detail loading failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
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
            return ['error' => false, 'message' => 'Invoice due update failed - ' . Helper::processErrorMessages($invoice->getErrors())];
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
                return ['error' => true, 'message' => 'Invoice Detail create failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
            }
        } else {
            return ['error' => true, 'message' => 'Invoice Detail loading failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
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
            return ['error' => false, 'message' => 'Invoice due update failed - ' . Helper::processErrorMessages($invoice->getErrors())];
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
                return ['error' => true, 'message' => 'Invoice Details validation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
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
                return ['error' => true, 'message' => 'Service update failed - ' . Helper::processErrorMessages($serviceObject->getErrors())];
            }
        }

        // Invoice Details insert process
        if (empty($invoiceDetailBatchData)) {
            return ['error' => true, 'message' => 'Invoice Detail Batch Data can not be empty.'];
        }
        if (!InvoiceRepository::batchStore(InvoiceDetail::tableName(), array_keys($invoiceDetailBatchData[0]), $invoiceDetailBatchData)) {
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
            return ['error' => true, 'message' => 'Invoice Detail creation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetail = $this->invoiceRepository->findOne(['refId' => $newReissueService->motherTicketId, 'refModel' => $newReissueService::class], InvoiceDetail::class, []);
        $motherInvoiceDetail->status = ServiceConstant::INVOICE_DETAIL_REISSUE_STATUS;
        $motherInvoiceDetail = $this->invoiceRepository->store($motherInvoiceDetail);
        if ($motherInvoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed - ' . Helper::processErrorMessages($motherInvoiceDetail->getErrors())];
        }

        // Invoice due update
        $invoice = $this->invoiceRepository->findOne(['id' => $newReissueService->invoiceId], Invoice::class, []);
        $invoice->dueAmount += $invoiceDetail->dueAmount;
        $invoice = $this->invoiceRepository->store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice due update failed - ' . Helper::processErrorMessages($invoice->getErrors())];
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
                throw new Exception('Invoice update failed for payment - ' . Helper::processErrorMessages($invoice->getErrors()));
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
                        throw new Exception('Refund Adjustment not save' . Helper::processErrorMessages($singleRefundTransaction->getErrors()));
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
                return ['error' => true, 'message' => Helper::processErrorMessages($invoiceDetail->getErrors())];
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

            // Service Payment Details entry   for customer
            /*$servicePaymentDetailResponse = ServicePaymentDetail::storeServicePaymentDetail($service['refModel'], $service['refId'], $invoice::class, $invoice->id, $service['paidAmount'], $service['dueAmount'], $user);
            if ($servicePaymentDetailResponse['error']) {
                return ['error' => true, 'message' => $servicePaymentDetailResponse['message']];
            }*/

            // Service Payment Details entry  for Supplier
            /*if (!empty($service['supplierData'])) {
                foreach ($service['supplierData'] as $supplierDatum) {
                    $servicePaymentDetailResponse = ServicePaymentDetail::storeServicePaymentDetail($supplierDatum['refModel'], $supplierDatum['refId'], $supplierDatum['subRefModel'] ?? null, $supplierDatum['subRefId'] ?? null, $supplierDatum['paidAmount'], $supplierDatum['dueAmount'], $user);
                    if ($servicePaymentDetailResponse['error']) {
                        return ['error' => true, 'message' => $servicePaymentDetailResponse['message']];
                    }
                }
            }*/

            // Update InvoiceId column in Service (Ticket/Hotel/Visa/Package etc) Model
            /*$AllServices = $service['refModel']::find()->where(['id' => $service['refId']])->all();
            foreach ($AllServices as $storedService) {
                $storedService->invoiceId = $invoice->id;
                if (!$storedService->save()) {
                    return ['error' => true, 'message' => Utils::processErrorMessages($storedService->getErrors())];
                }
            }*/
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
            return ['error' => true, 'message' => 'Invoice Details store failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
        }

        return ['error' => false, 'message' => 'Success'];
    }

    public function getBankList(): array
    {
        return ArrayHelper::map(BankAccount::findAll(['status' => GlobalConstant::ACTIVE_STATUS]), 'id', 'name');
    }

}