<?php

namespace app\modules\account\services;

use app\components\AttachmentFile;
use app\components\Constant;
use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Invoice;
use app\modules\account\models\InvoiceDetail;
use app\modules\account\models\ServicePaymentTimeline;
use app\modules\account\repositories\InvoiceRepository;
use app\modules\account\repositories\PaymentTimelineRepository;
use app\modules\admin\models\User;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\repositories\HolidayRepository;
use app\modules\sales\models\ServicePaymentDetail;
use PhpParser\Node\Expr\Cast\Double;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class InvoiceService
{
    private InvoiceRepository $invoiceRepository;

    public function __construct()
    {
        $this->invoiceRepository = new InvoiceRepository();
    }
    public static function storeInvoice($requestData, ActiveRecord $invoice): array
    {
        if (!$invoice->load(['Invoice' => $requestData['Invoice']])) {
            return ['error' => true, 'message' => 'Invoice loading failed'];
        }
        $totalDue = 0;
        $totalReceived = 0;
        $user = User::findOne(Yii::$app->user->id);
        foreach ($requestData['services'] as $service) {
            $serviceObj = json_decode($service);
            $totalDue += $serviceObj->due;
            $totalReceived += $serviceObj->amount;
        }

        $invoice->due = $totalDue;
        $invoice->amount = $totalReceived;
        $invoice->date = date('Y-m-d');
        $invoice->invoiceNumber = Helper::invoiceNumber();

        if ($invoice->save()) {
            AttachmentFile::uploadsById($invoice, 'invoiceFile');
            $serviceData = [];
            foreach ($requestData['services'] as $key => $service) {
                $serviceObj = json_decode($service);
                $serviceData[$key]['refModel'] = $serviceObj->refModel;
                $serviceData[$key]['refId'] = $serviceObj->refId;
                $serviceData[$key]['subRefModel'] = Invoice::className();
                $serviceData[$key]['subRefId'] = $invoice->id;
                $serviceData[$key]['amount'] = 0;
                $serviceData[$key]['due'] = $serviceObj->amount;
            }
            $serviceDataProcessResponse = ServiceComponent::serviceDataProcessForInvoice($invoice, $serviceData, $user);
            if ($serviceDataProcessResponse['error']) {
                return ['error' => true, 'message' => 'Service Data process failed - ' . $serviceDataProcessResponse['message']];
            }

            // Customer Ledger process
            $ledgerRequestData = [
                'title' => 'Service Purchase',
                'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                'refId' => $invoice->customerId,
                'refModel' => Customer::className(),
                'subRefId' => $invoice->id,
                'subRefModel' => $invoice::className(),
                'debit' => $invoice->amount,
                'credit' => 0
            ];
            $ledgerRequestResponse = LedgerComponent::createNewLedger($ledgerRequestData);
            if ($ledgerRequestResponse['error']) {
                return ['error' => true, 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
            }

            return ['error' => false, 'message' => 'Invoice created successfully'];
        } else {
            return ['error' => true, 'message' => Utils::processErrorMessages($invoice->getErrors())];
        }
    }

    public static function autoInvoice(int $customerId, array $services): array
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
        $invoice = InvoiceRepository::store($invoice);
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
        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'message' => 'Invoice created successfully', 'data' => $invoice];
    }

    public static function autoInvoiceForRefund(Invoice $invoice, array $service, $user): array
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
        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail];
    }

    public static function autoInvoiceForReissue(Invoice $invoice, array $service, $user): array
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
        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => true, 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail];
    }

    private static function serviceProcess(Invoice $invoice, array $services): array
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

    public static function addRefundServiceToInvoice(ActiveRecord $newRefundService): array
    {
        $invoiceDetail = new InvoiceDetail();
        $invoiceDetail->invoiceId = $newRefundService->invoiceId;
        $invoiceDetail->dueAmount = ($newRefundService->quoteAmount - $newRefundService->receivedAmount);
        $invoiceDetail->paidAmount = $newRefundService->receivedAmount;
        $invoiceDetail->refId = $newRefundService->id;
        $invoiceDetail->refModel = $newRefundService::class;
        $invoiceDetail->status = GlobalConstant::ACTIVE_STATUS;
        $invoiceDetail = InvoiceRepository::store($invoiceDetail);
        if ($invoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice Detail creation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetail = InvoiceRepository::findOne(['refId' => $newRefundService->motherTicketId, 'refModel' => $newRefundService::class], InvoiceDetail::class, []);
        $motherInvoiceDetail->status = 2;
        $motherInvoiceDetail = InvoiceRepository::store($motherInvoiceDetail);
        if ($motherInvoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed - ' . Helper::processErrorMessages($motherInvoiceDetail->getErrors())];
        }

        // Invoice due update
        $invoice = InvoiceRepository::findOne(['id' => $newRefundService->invoiceId], Invoice::class, ['details']);
        $invoiceDetailArray = ArrayHelper::toArray($invoice->details);
        $invoice->dueAmount = (double)array_sum(array_column($invoiceDetailArray, 'dueAmount'));
        $invoice = InvoiceRepository::store($invoice);
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

        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => $ledgerRequestResponse['error'], 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail];
    }

    public static function addReissueServiceToInvoice(ActiveRecord $newReissueService): array
    {
        // Invoice detail process
        $invoiceDetail = new InvoiceDetail();
        $invoiceDetail->invoiceId = $newReissueService->invoiceId;
        $invoiceDetail->dueAmount = ($newReissueService->quoteAmount - $newReissueService->receivedAmount);
        $invoiceDetail->paidAmount = $newReissueService->receivedAmount;
        $invoiceDetail->refId = $newReissueService->id;
        $invoiceDetail->refModel = $newReissueService::class;
        $invoiceDetail->status = GlobalConstant::ACTIVE_STATUS;
        $invoiceDetail = InvoiceRepository::store($invoiceDetail);
        if ($invoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Invoice Detail creation failed - ' . Helper::processErrorMessages($invoiceDetail->getErrors())];
        }

        // Mother Invoice Detail status update
        $motherInvoiceDetail = InvoiceRepository::findOne(['refId' => $newReissueService->motherTicketId, 'refModel' => $newReissueService::class], InvoiceDetail::class, []);
        $motherInvoiceDetail->status = ServiceConstant::INVOICE_DETAIL_REISSUE_STATUS;
        $motherInvoiceDetail = InvoiceRepository::store($motherInvoiceDetail);
        if ($motherInvoiceDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Mother Invoice details update failed - ' . Helper::processErrorMessages($motherInvoiceDetail->getErrors())];
        }

        // Invoice due update
        $invoice = InvoiceRepository::findOne(['id' => $newReissueService->invoiceId], Invoice::class);
        $invoice->dueAmount += $invoiceDetail->dueAmount;
        $invoice = InvoiceRepository::store($invoice);
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

        $ledgerRequestResponse = LedgerService::store($ledgerRequestData);
        if ($ledgerRequestResponse['error']) {
            return ['error' => $ledgerRequestResponse['error'], 'message' => 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']];
        }

        return ['error' => false, 'data' => $invoiceDetail->invoice];
    }

    public static function checkAndDetectPaymentStatus($due, $amount): string
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

    public static function offlineInvoicePayment(Invoice $invoice, array $requestData): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($invoice->due <= 0) {
                throw  new Exception('You are not allowed to perform this action. This invoice is already paid.');
            }
            $oldPaymentCharge = $invoice->paymentCharge;
            $oldAmount = $invoice->amount;

            $invoice->load($requestData);

            $customer = $invoice->customer;

            // Distribution amount set if coupon found
            /*if ($invoice->paymentMode == Constant::PAYMENT_MODE['Coupon']) {
                $couponCode = $invoice->chequeNumber;
                $couponValidationResponse = InvoiceService::couponCodeCheck($customer, $couponCode);
                if ($couponValidationResponse->code == 'SUCCESS') {
                    $invoice->amount += $couponValidationResponse->response->discount;
                } else {
                    throw new Exception($couponValidationResponse->message);
                }
            }*/

            // Distribution amount set if Refund Adjustment found
            if (Yii::$app->request->post('Invoice')['refundId']) {
                $invoice->amount += $invoice->adjustmentAmount;
            }

            $distributionAmount = $invoice->amount;
            $invoice->due = $invoice->due - $distributionAmount;
            $invoice->paymentCharge = $invoice->paymentCharge + $oldPaymentCharge;
            $invoice->amount = $distributionAmount + $oldAmount;
            $invoice->updatedBy = Yii::$app->user->id;
            $invoice->updatedAt = time();

            //process bank account
            $bank = BankAccount::findOne(['id' => Yii::$app->request->post('Invoice')['bankId']]);
            if (!$bank) {
                throw new Exception('Bank Not found');
            }

            if (!$invoice->save()) {
                throw new Exception('Invoice not saved  ' . Helper::processErrorMessages($invoice->getErrors()));
            }

            // Refund status update
            if ($invoice->refundId) {
                $refund = RefundTransaction::findOne(['id' => (int)$invoice->refundId]);
                if (!$refund) {
                    throw new Exception('Refund Adjustment Failed');
                }
                $refund->adjustAmount = $invoice->adjustmentAmount;
                $refund->adjusted = 1;
                if (!$refund->save()) {
                    throw new Exception('Refund Adjustment not save' . Utils::processErrorMessages($refund->getErrors()));
                }

            }

            AttachmentFile::uploadsById($invoice, 'invoiceFile');
            // Amount distributions

            $invoiceDetails = InvoiceDetail::find()->select(['refId', 'refModel'])->where(['invoiceId' => $invoice->id])->all();

            if (!count($invoiceDetails)) {
                throw new Exception('No invoice details found');
            }
            $amountDistributionResponse = InvoiceService::distributeAmountToServices($invoiceDetails, $distributionAmount, $invoice->creator);
            if ($amountDistributionResponse['error']) {
                throw new Exception($amountDistributionResponse['message']);
            }

            // Customer Ledger process
            $customerLedgerRequestData = [
                'title' => 'Payment received',
                'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                'refId' => $invoice->customerId,
                'refModel' => Customer::className(),
                'subRefId' => $invoice->id,
                'subRefModel' => $invoice::className(),
                'debit' => 0,
                'credit' => $distributionAmount
            ];
            $customerLedgerRequestResponse = LedgerComponent::createNewLedger($customerLedgerRequestData);
            if ($customerLedgerRequestResponse['error']) {
                throw new Exception('Customer Ledger creation failed - ' . $customerLedgerRequestResponse['message']);
            }

            // Bank Ledger process
            $bankLedgerRequestData = [
                'title' => 'Service Payment received',
                'reference' => 'Invoice Number - ' . $invoice->invoiceNumber,
                'refId' => $bank->id,
                'refModel' => $bank::className(),
                'subRefId' => $invoice->id,
                'subRefModel' => $invoice::className(),
                'debit' => $distributionAmount,
                'credit' => 0
            ];

            $bankLedgerRequestResponse = LedgerComponent::createNewLedger($bankLedgerRequestData);
            if ($bankLedgerRequestResponse['error']) {
                throw new Exception('Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
            }

            // Transaction statement process
            $requestData['Invoice']['paymentDate'] = $requestData['Invoice']['date'];
            $transactionRequestData = TransactionStatementComponent::formDataForTransactionStatement($requestData['Invoice'], $invoice->id, $invoice::className(), $invoice->customerId, Customer::class, Yii::$app->user->id);
            $transactionStatementStoreResponse = TransactionStatementComponent::store($transactionRequestData);
            if ($transactionStatementStoreResponse['error']) {
                throw new Exception('Transaction Statement creation failed - ' . $transactionStatementStoreResponse['message']);
            }
            $transaction->commit();

            return ['error' => false, 'message' => 'Invoice paid successfully.'];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return ['error' => true, 'message' => $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine()];
        }
    }

    public static function distributeAmountToServices($invoiceDetails, $amount, $user): array
    {
        foreach ($invoiceDetails as $invoiceDetail) {
            if ($amount <= 0) {
                break;
            }
            $service = $invoiceDetail->refModel::findOne(['id' => $invoiceDetail->refId]);
            if (!$service) {
                return ['error' => true, 'message' => "{$invoiceDetail->refModel} not found with id {$invoiceDetail->refId}"];
            }
            if ($service->paymentStatus == 'Full Paid') {
                continue;
            }
            $due = $service->quoteAmount - $service->receivedAmount;
            if ($due <= $amount) {
                $service->receivedAmount += $due;
                $service->paymentStatus = \app\modules\sales\components\Constant::PAYMENT_STATUS['Full Paid'];
                $paidAmountThisTime = $due;
                $amount -= $due;
            } else {
                $service->receivedAmount += $amount;
                $service->paymentStatus = \app\modules\sales\components\Constant::PAYMENT_STATUS['Partially Paid'];
                $paidAmountThisTime = $amount;
                $amount = 0;
            }
            $amountDue = $service->quoteAmount - $service->receivedAmount;
            if (!$service->save()) {
                return ['error' => true, 'message' => "{$invoiceDetail->refModel} not updated with id {$invoiceDetail->refId}"];
            }
            $servicePaymentDetailsStoreResponse = ServicePaymentDetail::storeServicePaymentDetail($invoiceDetail->refModel, $invoiceDetail->refId, Invoice::class, $service->invoiceId, $paidAmountThisTime, $amountDue, $user);
            if ($servicePaymentDetailsStoreResponse['error']) {
                return ['error' => true, 'message' => $servicePaymentDetailsStoreResponse['message']];
            }
            $invoiceDetailsResponse = InvoiceDetail::storeOrUpdateInvoiceDetail($service->invoiceId, $invoiceDetail->refModel, $invoiceDetail->refId, $service->receivedAmount, $amountDue);
            if ($invoiceDetailsResponse['error']) {
                return ['error' => true, 'message' => $invoiceDetailsResponse['message']];
            }

        }

        return ['error' => false, 'message' => "Distribution has been made successfully"];
    }

    public function updateInvoice($invoice, $services): void
    {

    }
}