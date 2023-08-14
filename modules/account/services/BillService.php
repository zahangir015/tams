<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Bill;
use app\modules\account\models\BillDetail;
use app\modules\account\models\RefundTransaction;
use app\modules\account\repositories\BillRepository;
use app\modules\account\repositories\InvoiceRepository;
use app\modules\admin\models\User;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Supplier;
use app\modules\sale\services\SaleService;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class BillService
{
    private BillRepository $billRepository;
    private TransactionService $transactionService;
    private LedgerService $ledgerService;
    private RefundTransactionService $refundTransactionService;

    public function __construct()
    {
        $this->billRepository = new BillRepository();
        $this->transactionService = new TransactionService();
        $this->refundTransactionService = new RefundTransactionService();
        $this->ledgerService = new LedgerService();
    }

    public function storeBill($requestData, ActiveRecord $bill): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!array_key_exists('services', $requestData)) {
                throw new Exception('Service Data is required.');
            }

            if (!$bill->load(['Bill' => $requestData['Bill']])) {
                throw new Exception('Bill loading failed.');
            }

            // Bill data process
            $totalDistributingAmount = ($requestData['Transaction']['paidAmount'] + $requestData['Transaction']['refundAdjustmentAmount'] + $bill->discountedAmount);
            $bill->date = date('Y-m-d');
            $bill->paidAmount = $totalDistributingAmount;
            $bill->dueAmount = ($bill->dueAmount - $totalDistributingAmount);
            $bill = $this->billRepository->store($bill);
            if ($bill->hasErrors()) {
                throw new Exception('Bill creation failed - ' . Utilities::processErrorMessages($bill->getErrors()));
            }

            // Bill detail data process
            /*$totalDue = 0;
            $totalReceived = 0;*/

            $serviceData = [];
            foreach ($requestData['services'] as $key => $service) {
                $serviceObj = json_decode($service);
                /*$totalDue += $serviceObj->dueAmount;
                $totalReceived += $serviceObj->paidAmount;*/
                $serviceData[$key]['refModel'] = $serviceObj->refModel;
                $serviceData[$key]['refId'] = $serviceObj->refId;
                $serviceData[$key]['subRefModel'] = Bill::class;
                $serviceData[$key]['subRefId'] = $bill->id;
                $serviceData[$key]['paidAmount'] = ($totalDistributingAmount >= $serviceObj->dueAmount) ? $serviceObj->dueAmount : $totalDistributingAmount;
                $serviceData[$key]['dueAmount'] = ($totalDistributingAmount >= $serviceObj->dueAmount) ?  0 : ($serviceObj->dueAmount - $totalDistributingAmount);
                $totalDistributingAmount = ($totalDistributingAmount >= $serviceObj->dueAmount) ? ($totalDistributingAmount - $serviceObj->dueAmount) : 0;
            }

            // Service Data process
            $serviceDataProcessResponse = self::serviceDataProcessForBill($bill, $serviceData, User::findOne(Yii::$app->user->id));
            if ($serviceDataProcessResponse['error']) {
                throw new Exception('Service Data process failed - ' . $serviceDataProcessResponse['message']);
            }

            // Process Transaction Data
            $transactionStatementStoreResponse = $this->transactionService->store($bill, $bill->supplier, $requestData);
            if ($transactionStatementStoreResponse['error']) {
                throw new Exception('Transaction Statement Data process failed - ' . $transactionStatementStoreResponse['message']);
            }
            $transaction = $transactionStatementStoreResponse['data'];

            // Supplier Ledger process
            $ledgerRequestData = [
                'title' => 'Supplier Bill payment',
                'reference' => 'Bill Number - ' . $bill->billNumber,
                'refId' => $bill->supplierId,
                'refModel' => Supplier::class,
                'subRefId' => $bill->id,
                'subRefModel' => $bill::class,
                'debit' => 0,
                'credit' => $bill->paidAmount
            ];
            $ledgerRequestResponse = $this->ledgerService->store($ledgerRequestData);
            if ($ledgerRequestResponse['error']) {
                throw new Exception('Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
            }

            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Invoice created successfully.');
            return [
                'error' => false,
                'message' => 'Bill added successfully.',
                'model' => $bill
            ];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getPendingBill(array $requestData): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $supplierId = $requestData['supplierId'];
        if (!isset($requestData['supplierId'])) {
            return [
                'html' => '',
                'totalPayable' => 0,
                'message' => 'Supplier info is required'
            ];
        }

        $start_date = $end_date = null;
        if (isset($requestData['dateRange']) && strpos($requestData['dateRange'], '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $requestData['dateRange']);
        }

        $pendingServices = Supplier::find()
            ->select(['id', 'name', 'company'])
            ->with([
                'tickets' => function ($query) use ($start_date, $end_date) {
                    $query->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'billId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'visas' => function ($query) use ($start_date, $end_date) {
                    $query->with(['visa'])->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'billId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'hotels' => function ($query) use ($start_date, $end_date) {
                    $query->with(['hotel'])->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'billId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'holidays' => function ($query) use ($start_date, $end_date) {
                    $query->with(['holiday'])->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'billId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
            ])
            ->where(['id' => $supplierId])->one();
        $html = '';
        $key = 1;
        $totalPayable = 0;
        if (!empty($pendingServices->tickets)) {
            foreach ($pendingServices->tickets as $pending) {
                $totalPayable += ($pending->costOfSale - $pending->paidAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->paidAmount,
                        'dueAmount' => ($pending->costOfSale - $pending->paidAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->eTicket . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->costOfSale - $pending->paidAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->costOfSale - $pending->paidAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->hotels)) {
            foreach ($pendingServices->hotels as $pending) {
                $totalPayable += ($pending->costOfSale - $pending->paidAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->paidAmount,
                        'dueAmount' => ($pending->costOfSale - $pending->paidAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->hotel->identificationNumber . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->costOfSale - $pending->paidAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->costOfSale - $pending->paidAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->visas)) {
            foreach ($pendingServices->visas as $pending) {
                $dueAmount = ($pending->costOfSale - $pending->paidAmount);
                $totalPayable += $dueAmount;
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->paidAmount,
                        'dueAmount' => $dueAmount,
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->visa->identificationNumber ?? 'N/A' . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . $dueAmount . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . $dueAmount . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->holidays)) {
            foreach ($pendingServices->holidays as $pending) {
                $dueAmount = ($pending->costOfSale - $pending->paidAmount);
                $totalPayable += $dueAmount;
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->paidAmount,
                        'dueAmount' => $dueAmount,
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->holiday->identificationNumber ?? 'NA' . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . $dueAmount . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . $dueAmount . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }

        return [
            'html' => $html,
            'totalPayable' => $totalPayable,
        ];
    }

    public function storeOrUpdateBillDetail(ActiveRecord $bill, mixed $service, mixed $user): array
    {
        $billDetail = $this->billRepository->findOne(['refModel' => $service['refModel'], 'refId' => $service['refId'], 'billId' => $bill->id], BillDetail::class, []);
        if ($billDetail) {
            $billDetail->dueAmount = $service['dueAmount'];
            $billDetail->paidAmount = $service['paidAmount'];
        } else {
            $billDetail = new BillDetail();
            $billDetail->load(['BillDetail' => $service]);
            $billDetail->billId = $bill->id;
            $billDetail->status = GlobalConstant::ACTIVE_STATUS;
        }

        $billDetail = $this->billRepository->store($billDetail);
        if ($billDetail->hasErrors()) {
            return ['error' => true, 'message' => 'Bill Details store failed - ' . Utilities::processErrorMessages($billDetail->getErrors())];
        }

        return ['error' => false, 'message' => 'Successfully stored.'];
    }

    public function serviceDataProcessForBill(ActiveRecord $bill, array $services, mixed $user): array
    {
        $updatableServices = [];
        foreach ($services as $service) {
            // Bill details entry
            $billDetailResponse = self::storeOrUpdateBillDetail($bill, $service, $user);
            if ($billDetailResponse['error']) {
                return $billDetailResponse;
            }
            $updatableServices[] = [
                'refModel' => $service['refModel'],
                'query' => ['id' => $service['refId']],
                'data' => ['billId' => $bill->id]
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


    public function addRefundServiceToInvoice(ActiveRecord $newRefundService): array
    {
        $invoiceDetail = new InvoiceDetail();
        $invoiceDetail->invoiceId = $newRefundService->invoiceId;
        $invoiceDetail->dueAmount = ($newRefundService->costOfSale - $newRefundService->paidAmount);
        $invoiceDetail->paidAmount = $newRefundService->paidAmount;
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
            /*$processedData = PaymentTimelineService::processData($invoice, $singleService);
            $paymentTimelineBatchData = array_merge($paymentTimelineBatchData, $processedData);*/

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
        /*$paymentTimelineProcessResponse = PaymentTimelineService::batchInsert($paymentTimelineBatchData);
        if ($paymentTimelineProcessResponse['error']) {
            return $paymentTimelineProcessResponse;
        }*/

        return ['error' => false, 'message' => 'Service process done.'];
    }

    public function addReissueServiceToInvoice(ActiveRecord $newReissueService): array
    {
        // Invoice detail process
        $invoiceDetail = new InvoiceDetail();
        $invoiceDetail->invoiceId = $newReissueService->invoiceId;
        $invoiceDetail->dueAmount = ($newReissueService->costOfSale - $newReissueService->paidAmount);
        $invoiceDetail->paidAmount = $newReissueService->paidAmount;
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
            $due = $service->costOfSale - $service->paidAmount;
            if (($service->paymentStatus == ServiceConstant::PAYMENT_STATUS['Full Paid']) && ($due == 0)) {
                continue;
            }
            if ($due <= $amount) {
                $service->paidAmount += $due;
                $service->paymentStatus = ServiceConstant::PAYMENT_STATUS['Full Paid'];
                //$paidAmountThisTime = $due;
                $amount -= $due;
            } else {
                $service->paidAmount += $amount;
                $service->paymentStatus = ServiceConstant::PAYMENT_STATUS['Partially Paid'];
                //$paidAmountThisTime = $amount;
                $amount = 0;
            }
            $amountDue = $service->costOfSale - $service->paidAmount;
            if (!$service->save()) {
                return ['error' => true, 'message' => "{$invoiceDetail->refModel} not updated with id {$invoiceDetail->refId}"];
            }

            // Invoice detail update
            $invoiceDetail->dueAmount = $amountDue;
            $invoiceDetail->paidAmount = $service->paidAmount;
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


    protected function storeOrUpdateInvoiceDetail(ActiveRecord $invoice, $service, $user): array
    {
        $invoiceDetail = $this->invoiceRepository->findOne(['refModel' => $service['refModel'], 'refId' => $service['refId'], 'invoiceId' => $invoice->id], InvoiceDetail::class, []);
        if ($invoiceDetail) {
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
        return ArrayHelper::map(BankAccount::findAll(['status' => GlobalConstant::ACTIVE_STATUS, 'agencyId' => Yii::$app->user->identity->agencyId]), 'id', 'name');
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
                        'paidAmount' => sprintf('%.2f', $invoiceDetail->amount),
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
        $invoiceDue = InvoiceDetail::find()->select([new Expression('SUM(dueAmount) AS dueAmount')])->where(['status' => 1])->andWhere(['invoiceId' => $invoiceDetail->invoiceId])->asArray()->one();
        if (!$invoiceDue) {
            return ['error' => true, 'message' => 'Invoice due calculation failed.'];
        }
        $invoice->dueAmount = $invoiceDue['dueAmount'];
        $invoice = (new InvoiceRepository)->store($invoice);
        if ($invoice->hasErrors()) {
            return ['error' => true, 'message' => "Invoice due update failed - " . Utilities::processErrorMessages($invoice->getErrors())];
        }

        return ['error' => false, 'message' => "Invoice due updated successfully", 'data' => $invoice];
    }

}