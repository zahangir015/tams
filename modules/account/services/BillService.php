<?php

namespace app\modules\account\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\models\BankAccount;
use app\modules\account\models\Bill;
use app\modules\account\models\BillDetail;
use app\modules\account\models\Invoice;
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

    public function payment(ActiveRecord $bill, array $requestData): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // If bill is already paid
            if ($bill->dueAmount <= 0) {
                throw  new Exception('Invalid request. This bill is already paid.');
            }

            // If distribution amount is greater than dueAmount
            $totalDistributingAmount = ($requestData['Transaction']['paidAmount'] + $requestData['Transaction']['refundAdjustmentAmount'] + $bill->discountedAmount);
            if ($bill->dueAmount < $totalDistributingAmount) {
                throw  new Exception('Invalid Request. This bill will be over paid.');
            }

            $bill->paidAmount += $totalDistributingAmount;
            $bill->dueAmount -= $totalDistributingAmount;
            $bill = $this->billRepository->store($bill);
            if ($bill->hasErrors()) {
                throw new Exception('Bill payment failed - ' . Utilities::processErrorMessages($bill->getErrors()));
            }

            $amountDistributionResponse = self::distributePaidAmountToServices($bill, $totalDistributingAmount);
            if ($amountDistributionResponse['error']) {
                throw new Exception($amountDistributionResponse['message']);
            }

            // Process Transaction Data
            $transactionStatementStoreResponse = $this->transactionService->store($bill, $bill->supplier, $requestData);
            if ($transactionStatementStoreResponse['error']) {
                throw new Exception('Transaction Statement Data process failed - ' . $transactionStatementStoreResponse['message']);
            }
            $transaction = $transactionStatementStoreResponse['data'];

            // Supplier Ledger process
            $supplierLedgerRequestData = [
                'title' => 'Bill Paid',
                'reference' => 'Bill Number - ' . $bill->billNumber,
                'refId' => $bill->supplierId,
                'refModel' => Supplier::class,
                'subRefId' => $bill->id,
                'subRefModel' => $bill::class,
                'debit' => $totalDistributingAmount,
                'credit' => 0
            ];
            $supplierLedgerRequestResponse = $this->ledgerService->store($supplierLedgerRequestData);
            if ($supplierLedgerRequestResponse['error']) {
                throw new Exception('Customer Ledger creation failed - ' . $supplierLedgerRequestResponse['message']);
            }

            // Bank Ledger process
            $bankLedgerRequestData = [
                'title' => 'Bill Paid',
                'reference' => 'Bill Number - ' . $bill->invoiceNumber,
                'refId' => $transaction->bankId,
                'refModel' => BankAccount::class,
                'subRefId' => $bill->id,
                'subRefModel' => $bill::class,
                'debit' => 0,
                'credit' => $transaction->paidAmount
            ];
            $bankLedgerRequestResponse = $this->ledgerService->store($bankLedgerRequestData);
            if ($bankLedgerRequestResponse['error']) {
                throw new Exception('Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Bill paid successfully.'];
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    private function distributePaidAmountToServices($bill, $amount): array
    {
        foreach ($bill->details as $billDetail) {
            if ($amount <= 0) {
                break;
            }
            $service = $this->billRepository->findOne(['id' => $billDetail->refId], $billDetail->refModel);
            if (!$service) {
                return ['error' => true, 'message' => "Service not found."];
            }

            $due = $service->costOfSale - $service->paidAmount;
            if (($service->paymentStatus == ServiceConstant::PAYMENT_STATUS['Full Paid']) && ($due == 0)) {
                continue;
            }
            if ($due <= $amount) {
                $service->paidAmount += $due;
                $service->paymentStatus = ServiceConstant::PAYMENT_STATUS['Full Paid'];
                $amount -= $due;
            } else {
                $service->paidAmount += $amount;
                $service->paymentStatus = ServiceConstant::PAYMENT_STATUS['Partially Paid'];
                $amount = 0;
            }
            $amountDue = $service->costOfSale - $service->paidAmount;
            if (!$service->save()) {
                return ['error' => true, 'message' => 'Service update failed - '.Utilities::processErrorMessages($service->getErrors())];
            }

            // Invoice detail update
            $billDetail->dueAmount = $amountDue;
            $billDetail->paidAmount = $service->paidAmount;
            $billDetail = $this->billRepository->store($billDetail);
            if ($billDetail->hasErrors()) {
                return ['error' => true, 'message' => 'Bill details update failed - '.Utilities::processErrorMessages($billDetail->getErrors())];
            }
        }

        return ['error' => false, 'message' => "Distribution has been made successfully."];
    }

    public function getBankList(): array
    {
        return ArrayHelper::map(BankAccount::findAll(['status' => GlobalConstant::ACTIVE_STATUS, 'agencyId' => Yii::$app->user->identity->agencyId]), 'id', 'name');
    }

}