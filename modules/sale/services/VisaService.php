<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\services\InvoiceService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\visa\Visa;
use app\modules\sale\models\visa\VisaRefund;
use app\modules\sale\models\visa\VisaSupplier;
use app\modules\sale\models\Supplier;
use app\modules\sale\repositories\VisaRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

class VisaService
{
    private VisaRepository $visaRepository;
    private InvoiceService $invoiceService;

    public function __construct()
    {
        $this->visaRepository = new VisaRepository();
        $this->invoiceService = new InvoiceService();
    }

    public function storeVisa(array $requestData): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (empty($requestData['Visa']) || empty($requestData['VisaSupplier'])) {
                throw new Exception('Visa and supplier data can not be empty.');
            }

            // Visa data processing
            $customer = Customer::findOne(['id' => $requestData['Visa']['customerId']]);
            $visa = new Visa();
            if (!$visa->load($requestData)) {
                throw new Exception('Visa data loading failed - ' . Utilities::processErrorMessages($visa->getErrors()));
            }
            $visa->type = ServiceConstant::TYPE['New'];
            $visa->customerCategory = $customer->category;
            $visa = $this->visaRepository->store($visa);
            if ($visa->hasErrors()) {
                throw new Exception('Visa create failed - ' . Utilities::processErrorMessages($visa->getErrors()));
            }

            // Visa Supplier data process
            foreach ($requestData['VisaSupplier'] as $singleSupplierArray) {
                $visaSupplier = new VisaSupplier();
                $visaSupplier->load(['VisaSupplier' => $singleSupplierArray]);
                $visaSupplier->visaId = $visa->id;
                $visaSupplier = $this->visaRepository->store($visaSupplier);
                if ($visaSupplier->hasErrors()) {
                    throw new Exception('Visa Supplier creation failed - ' . Utilities::processErrorMessages($visaSupplier->getErrors()));
                }
            }

            // Invoice process and create
            if (isset($requestData['invoice'])) {
                // Invoice details data process
                $services[] = [
                    'refId' => $visa->id,
                    'refModel' => Visa::class,
                    'dueAmount' => $visa->quoteAmount,
                    'paidAmount' => 0,
                ];
                // Auto invoice process
                $autoInvoiceCreateResponse = $this->invoiceService->autoInvoice($customer->id, $services);
                if ($autoInvoiceCreateResponse['error']) {
                    throw new Exception('Auto Invoice creation failed - ' . $autoInvoiceCreateResponse['message']);
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Visa added successfully', 'model' => $visa];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * @throws Exception
     */
    public
    function refundVisa(array $requestData, ActiveRecord $motherVisa): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Visa and supplier data can not be empty
            if (empty($requestData['Visa']) || empty($requestData['VisaSupplier'])) {
                throw new Exception('Visa and supplier data can not be empty.');
            }
            // Visa data processing
            $customer = Customer::findOne(['id' => $requestData['Visa']['customerId']]);
            $visa = new Visa();
            if (!$visa->load($requestData)) {
                throw new Exception('Visa data loading failed - ' . Utilities::processErrorMessages($visa->getErrors()));
            }
            $visa->customerCategory = $customer->category;
            $visa->invoiceId = $motherVisa->invoiceId;
            $visa = $this->visaRepository->store($visa);
            if ($visa->hasErrors()) {
                throw new Exception('Visa refund create failed - ' . Utilities::processErrorMessages($visa->getErrors()));
            }

            // Mother Visa update
            $motherVisa->type = ServiceConstant::TICKET_TYPE_FOR_REFUND['Refund Requested'];
            $motherVisa->refundRequestDate = $visa->refundRequestDate;
            $motherVisa = $this->visaRepository->store($motherVisa);
            if ($motherVisa->hasErrors()) {
                throw new Exception('Mother visa update failed - ' . Utilities::processErrorMessages($motherVisa->getErrors()));
            }

            // Visa Supplier data process
            foreach ($requestData['VisaSupplier'] as $singleSupplierArray) {
                $visaSupplier = new VisaSupplier();
                $visaSupplier->load(['VisaSupplier' => $singleSupplierArray]);
                $visaSupplier->visaId = $visa->id;
                $visaSupplier = $this->visaRepository->store($visaSupplier);
                if ($visaSupplier->hasErrors()) {
                    throw new Exception('Visa Supplier storing failed - ' . Utilities::processErrorMessages($visaSupplier->getErrors()));
                }
            }

            // Create refund for customer and supplier
            $refundDataProcessResponse = self::processRefundModelData($visa, $requestData);
            if ($refundDataProcessResponse['error']) {
                throw new Exception('Visa refund creation failed - ' . $refundDataProcessResponse['message']);
            }

            if ($motherVisa->invoiceId) {
                // Invoice details data process
                $service = [
                    'invoiceId' => $motherVisa->invoiceId ?? null,
                    'refId' => $visa->id,
                    'refModel' => Visa::class,
                    'dueAmount' => ($visa->quoteAmount - $visa->receivedAmount),
                    'paidAmount' => $visa->receivedAmount,
                    'motherId' => $motherVisa->id,
                ];

                // Invoice process
                $autoInvoiceCreateResponse = $this->invoiceService->autoInvoiceForRefund($motherVisa->invoice, $service, Yii::$app->user);
                if ($autoInvoiceCreateResponse['error']) {
                    throw new Exception('Auto Invoice creation failed - ' . $autoInvoiceCreateResponse['message']);
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Visa refund added successfully', 'model' => $visa];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public
    function updateVisa(array $requestData, Visa $visa): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Visa and supplier data can not be empty
            if (empty($requestData['Visa']) || empty($requestData['VisaSupplier'])) {
                throw new Exception('Visa and supplier data required.');
            }

            $oldQuoteAmount = $visa->quoteAmount;

            // Update Visa
            if ($visa->load($requestData)){
                throw new Exception('Visa data loading failed - ' . Utilities::processErrorMessages($visa->getErrors()));
            }
            $visa->netProfit = self::calculateNetProfit($visa->quoteAmount, $visa->costOfSale);
            $visa->paymentStatus = (new InvoiceService())->checkAndDetectPaymentStatus($visa->quoteAmount, $visa->receivedAmount);
            $visa = $this->visaRepository->store($visa);
            if ($visa->hasErrors()) {
                throw new Exception('Visa update failed - ' . Utilities::processErrorMessages($visa->getErrors()));
            }

            //Update Visa Supplier Entity
            $updateVisaSupplierResponse = $this->updateVisaSupplier($visa, $requestData['VisaSupplier']);
            if ($updateVisaSupplierResponse['error']) {
                throw new Exception($updateVisaSupplierResponse['message']);
            }

            // If invoice created and quote updated then process related data
            if (isset($visa->invoice) && ($oldQuoteAmount != $visa->quoteAmount)) {
                //Update Invoice Entity
                $services[] = [
                    'refId' => $visa->id,
                    'refModel' => Visa::class,
                    'due' => ($visa->quoteAmount - $visa->receivedAmount),
                    'amount' => $visa->receivedAmount
                ];

                $serviceRelatedDataProcessResponse = SaleService::updatedServiceRelatedData($visa, $services);
                if ($serviceRelatedDataProcessResponse['error']) {
                    throw new Exception($serviceRelatedDataProcessResponse['message']);
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Visa updated successfully', 'model' => $visa];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    private function updateVisaSupplier(ActiveRecord $visa, mixed $visaSuppliers): array
    {
        foreach ($visaSuppliers as $supplier) {
            if (isset($supplier['id'])) {
                $visaSupplier = $this->visaRepository->findOne(['id' => $supplier['id']], VisaSupplier::class, []);
            } else {
                $visaSupplier = new VisaSupplier();
                $visaSupplier->visaId = $visa->id;
                $visaSupplier->status = GlobalConstant::ACTIVE_STATUS;
                $visaSupplier->paymentStatus = ServiceConstant::PAYMENT_STATUS['Due'];
            }
            $visaSupplier->load(['VisaSupplier' => $supplier]);
            $visaSupplier->type = $supplier['type'] ?? ServiceConstant::TYPE['New'];
            $visaSupplier = $this->visaRepository->store($visaSupplier);
            if ($visaSupplier->hasErrors()) {
                return ['error' => true, 'message' => 'Visa Supplier update failed - ' . Utilities::processErrorMessages($visaSupplier->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'Visa Supplier updated successfully'];
    }


    public function updateRefundVisa(array $requestData, ActiveRecord $visa): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Visa and supplier data can not be empty
            if (empty($requestData['Visa']) || empty($requestData['VisaSupplier'])) {
                throw new Exception('Visa and supplier data is required.');
            }
            $oldQuoteAmount = $visa->quoteAmount;

            // Visa data processing
            $customer = Customer::findOne(['id' => $requestData['Visa']['customerId']]);
            if (!$visa->load($requestData)) {
                throw new Exception('Visa data loading failed - ' . Utilities::processErrorMessages($visa->getErrors()));
            }

            $visa = $this->visaRepository->store($visa);
            if ($visa->hasErrors()) {
                throw new Exception('Visa update failed - ' . Utilities::processErrorMessages($visa->getErrors()));
            }

            // Customer Refund data update
            $customerVisaRefund = VisaRefund::find()->where(['refId' => $customer->id, 'refModel' => 'app\\modules\\sale\\models\\Customer', 'visaId' => $visa->id])->one();
            if (!$customerVisaRefund) {
                throw new Exception('Customer Visa refund not found.');
            }
            $customerVisaRefund->load($requestData);
            $customerVisaRefund->serviceCharge = $visa->quoteAmount;
            $customerVisaRefund->refundRequestDate = $visa->refundRequestDate;
            $customerVisaRefund = $this->visaRepository->store($customerVisaRefund);
            if ($customerVisaRefund->hasErrors()) {
                throw new Exception('Customer visa refund update failed - ' . Utilities::processErrorMessages($customerVisaRefund->getErrors()));
            }

            // Mother Visa update
            $motherVisa = $this->visaRepository->findOne(['id' => $visa->motherId], Visa::class);
            $motherVisa->refundRequestDate = $visa->refundRequestDate;
            $motherVisa = $this->visaRepository->store($motherVisa);
            if ($motherVisa->hasErrors()) {
                throw new Exception('Mother visa update failed - ' . Utilities::processErrorMessages($motherVisa->getErrors()));
            }

            // Visa Supplier data process
            foreach ($requestData['VisaSupplier'] as $singleSupplierArray) {
                $visaSupplier = VisaSupplier::findOne(['id' => $singleSupplierArray['id']]);
                $visaSupplier->load(['VisaSupplier' => $singleSupplierArray]);
                $visaSupplier->visaId = $visa->id;
                $visaSupplier->refundRequestDate = $visa->refundRequestDate;
                $visaSupplier = $this->visaRepository->store($visaSupplier);
                if ($visaSupplier->hasErrors()) {
                    throw new Exception('Visa Supplier refund creation failed - ' . Utilities::processErrorMessages($visaSupplier->getErrors()));
                }

                // Supplier refund data update
                $supplierVisaRefund = VisaRefund::find()->where(['refId' => $visaSupplier->supplierId, 'refModel' => 'app\\modules\\sale\\models\\Supplier', 'visaId' => $visa->id])->one();
                if (!$supplierVisaRefund) {
                    throw new Exception('Supplier Visa refund not found.');
                }
                $supplierVisaRefund->load($requestData);
                $supplierVisaRefund->serviceCharge = $visaSupplier->costOfSale;
                $supplierVisaRefund->refundRequestDate = $visaSupplier->refundRequestDate;
                $supplierVisaRefund = $this->visaRepository->store($supplierVisaRefund);
                if ($supplierVisaRefund->hasErrors()) {
                    throw new Exception('Supplier visa refund update failed - ' . Utilities::processErrorMessages($supplierVisaRefund->getErrors()));
                }
            }

            // If invoice created and quote updated then process related data
            if (isset($visa->invoice) && ($oldQuoteAmount != $visa->quoteAmount)) {
                //Update Invoice Entity
                $services[] = [
                    'refId' => $visa->id,
                    'refModel' => Visa::class,
                    'dueAmount' => ($visa->quoteAmount - $visa->receivedAmount),
                    'paidAmount' => $visa->receivedAmount
                ];

                $serviceRelatedDataProcessResponse = SaleService::updatedServiceRelatedData($visa, $services);
                if ($serviceRelatedDataProcessResponse['error']) {
                    throw new Exception($serviceRelatedDataProcessResponse['message']);
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Refund Visa updated successfully.'];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => false, 'message' => $e->getMessage()];
        }
    }

    private
    function visaSupplierProcess(ActiveRecord $visa, mixed $visaSuppliers): array
    {
        foreach ($visaSuppliers as $singleSupplierArray) {
            $visaSupplier = new VisaSupplier();
            $visaSupplier->load(['VisaSupplier' => $singleSupplierArray]);
            $visaSupplier->visaId = $visa->id;
            $visaSupplier = $this->visaRepository->store($visaSupplier);
            if ($visaSupplier->hasErrors()) {
                return ['error' => true, 'message' => 'Visa Supplier creation failed - ' . Utilities::processErrorMessages($visaSupplier->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'Visa Supplier added successfully.'];
    }

    private
    function processRefundModelData($visa, array $requestData): array
    {
        $referenceData = [
            [
                'refId' => $visa->customerId,
                'refModel' => Customer::class,
                'serviceCharge' => $visa->quoteAmount,
                'visaId' => $visa->id,
                'refundRequestDate' => $visa->refundRequestDate,
                'isRefunded' => 0,
            ],
        ];

        foreach ($visa->visaSuppliers as $singleSupplier) {
            if ($singleSupplier->type == ServiceConstant::SERVICE_TYPE_FOR_CREATE['Refund']) {
                $referenceData[] = [
                    'refId' => $singleSupplier->supplierId,
                    'refModel' => Supplier::class,
                    'serviceCharge' => $singleSupplier->costOfSale,
                    'visaId' => $singleSupplier->id,
                    'refundRequestDate' => $visa->refundRequestDate,
                    'isRefunded' => 0,
                ];
            }
        }

        $visaRefundBatchData = [];
        // Customer Visa refund data process
        foreach ($referenceData as $ref) {
            $visaRefund = new VisaRefund();
            $visaRefund->load($requestData);
            $visaRefund->load(['VisaRefund' => $ref]);
            if (!$visaRefund->validate()) {
                return ['error' => true, 'message' => 'Visa Refund validation failed - ' . Utilities::processErrorMessages($visaRefund->getErrors())];
            }
            $visaRefundBatchData[] = $visaRefund->getAttributes(null, ['id']);
        }

        // Visa Refund batch insert process
        if (empty($visaRefundBatchData)) {
            return ['error' => true, 'message' => 'Visa Refund batch data process failed.'];
        }

        if (!$this->visaRepository->batchStore('visa_refund', array_keys($visaRefundBatchData[0]), $visaRefundBatchData)) {
            return ['error' => true, 'message' => 'Visa Refund batch insert failed'];
        }

        return ['error' => false, 'message' => 'Visa Refund process done.'];
    }

    private
    static function calculateNetProfit(mixed $quoteAmount, mixed $costOfSale)
    {
        return ($quoteAmount - $costOfSale);
    }

}