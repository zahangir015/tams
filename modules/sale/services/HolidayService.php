<?php

namespace app\modules\sale\services;

use app\components\Utilities;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidayRefund;
use app\modules\sale\models\holiday\HolidaySupplier;
use app\modules\sale\models\Supplier;
use app\modules\sale\repositories\HolidayRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class HolidayService
{
    private HolidayRepository $holidayRepository;
    private InvoiceService $invoiceService;

    public function __construct()
    {
        $this->holidayRepository = new HolidayRepository();
        $this->invoiceService = new InvoiceService();
    }

    /**
     * @throws Exception
     */
    public function storeHoliday(array $requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (empty($requestData['Holiday']) || empty($requestData['HolidaySupplier'])) {
                throw new Exception('Holiday and supplier data can not be empty.');
            }
            $services = [];
            $invoice = null;

            // Holiday data process and store
            $customer = Customer::findOne(['id' => $requestData['Holiday']['customerId']]);
            $holiday = new Holiday();
            if (!$holiday->load($requestData)) {
                throw new Exception('Holiday data loading failed - ' . Utilities::processErrorMessages($holiday->getErrors()));
            }
            $holiday->type = ServiceConstant::TYPE['New'];
            $holiday->customerCategory = $customer->category;
            $holiday = $this->holidayRepository->store($holiday);
            if ($holiday->hasErrors()) {
                throw new Exception('Holiday create failed - ' . Utilities::processErrorMessages($holiday->getErrors()));
            }

            // Holiday Supplier data process
            foreach ($requestData['HolidaySupplier'] as $singleSupplierArray) {
                $holidaySupplier = new HolidaySupplier();
                $holidaySupplier->load(['HolidaySupplier' => $singleSupplierArray]);
                $holidaySupplier->holidayId = $holiday->id;
                $holidaySupplier->refundRequestDate = $holiday->refundRequestDate;
                $holidaySupplier = $this->holidayRepository->store($holidaySupplier);
                if ($holidaySupplier->hasErrors()) {
                    throw new Exception('Holiday Supplier refund creation failed - ' . Utilities::processErrorMessages($holidaySupplier->getErrors()));
                }
            }

            // Invoice process and create
            if (isset($requestData['invoice'])) {
                // Invoice details data process
                $services[] = [
                    'refId' => $holiday->id,
                    'refModel' => Holiday::class,
                    'dueAmount' => $holiday->quoteAmount,
                    'paidAmount' => 0,
                ];
                // Invoice data process and storing
                $autoInvoiceCreateResponse = $this->invoiceService->autoInvoice($customer->id, $services);
                if ($autoInvoiceCreateResponse['error']) {
                    throw new Exception('Auto Invoice creation failed - ' . $autoInvoiceCreateResponse['message']);
                }
            }

            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Holiday added successfully');
            return true;
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage());
            return false;
        }
    }

    public
    function refundHoliday(array $requestData, Holiday $motherHoliday): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Holiday and supplier data can not be empty
            if (empty($requestData['Holiday']) || empty($requestData['HolidaySupplier'])) {
                throw new Exception('Holiday and supplier data can not be empty.');
            }

            // Holiday data processing
            $customer = Customer::findOne(['id' => $requestData['Holiday']['customerId']]);
            $holiday = new Holiday();
            if (!$holiday->load($requestData)) {
                throw new Exception('Holiday data loading failed - ' . Utilities::processErrorMessages($holiday->getErrors()));
            }
            $holiday->customerCategory = $customer->category;
            $holiday->invoiceId = $motherHoliday->invoiceId;
            $holiday = $this->holidayRepository->store($holiday);
            if ($holiday->hasErrors()) {
                throw new Exception('Holiday refund creation failed - ' . Utilities::processErrorMessages($holiday->getErrors()));
            }

            // Mother Holiday update
            $motherHoliday->type = ServiceConstant::TICKET_TYPE_FOR_REFUND['Refund Requested'];
            $motherHoliday->refundRequestDate = $holiday->refundRequestDate;
            $motherHoliday = $this->holidayRepository->store($motherHoliday);
            if ($motherHoliday->hasErrors()) {
                throw new Exception('Mother holiday update failed - ' . Utilities::processErrorMessages($motherHoliday->getErrors()));
            }

            // Holiday Supplier data process
            foreach ($requestData['HolidaySupplier'] as $singleSupplierArray) {
                $holidaySupplier = new HolidaySupplier();
                $holidaySupplier->load(['HolidaySupplier' => $singleSupplierArray]);
                $holidaySupplier->holidayId = $holiday->id;
                $holidaySupplier->refundRequestDate = $holiday->refundRequestDate;
                $holidaySupplier = $this->holidayRepository->store($holidaySupplier);
                if ($holidaySupplier->hasErrors()) {
                    throw new Exception('Holiday Supplier refund creation failed - ' . Utilities::processErrorMessages($holidaySupplier->getErrors()));
                }
            }

            // Create refund for customer and supplier
            $refundDataProcessResponse = self::processRefundModelData($holiday, $requestData);
            if ($refundDataProcessResponse['error']) {
                throw new Exception('Holiday refund creation failed - ' . $refundDataProcessResponse['message']);
            }

            // Invoice Process
            if ($motherHoliday->invoiceId) {
                // Invoice details data process
                $service = [
                    'invoiceId' => $motherHoliday->invoiceId ?? null,
                    'refId' => $holiday->id,
                    'refModel' => Holiday::class,
                    'dueAmount' => ($holiday->quoteAmount - $holiday->receivedAmount),
                    'paidAmount' => $holiday->receivedAmount,
                    'motherId' => $motherHoliday->id,
                ];
                $autoInvoiceDetailCreateResponse = $this->invoiceService->autoInvoiceForRefund($motherHoliday->invoice, $service, Yii::$app->user);
                if ($autoInvoiceDetailCreateResponse['error']) {
                    throw new Exception('Auto Invoice detail creation failed - ' . $autoInvoiceDetailCreateResponse['message']);
                }
            }

            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Holiday added successfully');
            return true;
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage());
            return false;
        }
    }

    public
    function updateHoliday(array $requestData, Holiday $holiday): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Holiday and supplier data can not be empty
            if (empty($requestData['Holiday']) || empty($requestData['HolidaySupplier'])) {
                throw new Exception('Holiday and supplier data required.');
            }

            $oldQuoteAmount = $holiday->quoteAmount;

            // Update Holiday
            if ($holiday->load($requestData)){
                throw new Exception('Holiday data loading failed - ' . Utilities::processErrorMessages($holiday->getErrors()));
            }
            $holiday->netProfit = self::calculateNetProfit($holiday->quoteAmount, $holiday->costOfSale);
            $holiday->paymentStatus = (new InvoiceService())->checkAndDetectPaymentStatus($holiday->quoteAmount, $holiday->receivedAmount);
            $holiday = $this->holidayRepository->store($holiday);
            if ($holiday->hasErrors()) {
                throw new Exception('Holiday update failed - ' . Utilities::processErrorMessages($holiday->getErrors()));
            }

            //Update Holiday Supplier Entity
            $updateHolidaySupplierResponse = $this->updateHolidaySupplier($holiday, $requestData['HolidaySupplier']);
            if ($updateHolidaySupplierResponse['error']) {
                throw new Exception($updateHolidaySupplierResponse['message']);
            }

            // If invoice created and quote updated then process related data
            if (isset($holiday->invoice) && ($oldQuoteAmount != $holiday->quoteAmount)) {
                //Update Invoice Entity
                $services[] = [
                    'refId' => $holiday->id,
                    'refModel' => Holiday::class,
                    'due' => ($holiday->quoteAmount - $holiday->receivedAmount),
                    'amount' => $holiday->receivedAmount
                ];

                $serviceRelatedDataProcessResponse = SaleService::updatedServiceRelatedData($holiday, $services);
                if ($serviceRelatedDataProcessResponse['error']) {
                    throw new Exception($serviceRelatedDataProcessResponse['message']);
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Holiday updated successfully', 'model' => $holiday];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    private function updateHolidaySupplier(ActiveRecord $holiday, mixed $holidaySuppliers): array
    {
        foreach ($holidaySuppliers as $supplier) {
            if (isset($supplier['id'])) {
                $holidaySupplier = $this->holidayRepository->findOne(['id' => $supplier['id']], HolidaySupplier::class, []);
            } else {
                $holidaySupplier = new HolidaySupplier();
                $holidaySupplier->holidayId = $holiday->id;
                $holidaySupplier->status = GlobalConstant::ACTIVE_STATUS;
                $holidaySupplier->paymentStatus = ServiceConstant::PAYMENT_STATUS['Due'];
            }
            $holidaySupplier->load(['HolidaySupplier' => $supplier]);
            $holidaySupplier->type = $supplier['type'] ?? ServiceConstant::TYPE['New'];
            $holidaySupplier = $this->holidayRepository->store($holidaySupplier);
            if ($holidaySupplier->hasErrors()) {
                return ['error' => true, 'message' => 'Holiday Supplier update failed - ' . Utilities::processErrorMessages($holidaySupplier->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'Holiday Supplier updated successfully'];
    }

    private
    function holidaySupplierProcess(ActiveRecord $holiday, mixed $holidaySuppliers): array
    {
        foreach ($holidaySuppliers as $singleSupplierArray) {
            $holidaySupplier = new HolidaySupplier();
            $holidaySupplier->load(['HolidaySupplier' => $singleSupplierArray]);
            $holidaySupplier->holidayId = $holiday->id;
            $holidaySupplier = $this->holidayRepository->store($holidaySupplier);
            if ($holidaySupplier->hasErrors()) {
                return ['error' => true, 'message' => 'Holiday Supplier creation failed - ' . Utilities::processErrorMessages($holidaySupplier->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'Holiday Supplier added successfully.'];
    }

    private
    function processRefundModelData(ActiveRecord $holiday, array $requestData): array
    {
        $referenceData = [
            [
                'refId' => $holiday->customerId,
                'refModel' => Customer::class,
                'serviceCharge' => $holiday->quoteAmount,
                'holidayId' => $holiday->id,
                'refundRequestDate' => $holiday->refundRequestDate,
                'isRefunded' => 0,
            ],
        ];

        foreach ($holiday->holidaySuppliers as $singleSupplier) {
            if ($singleSupplier->type == ServiceConstant::SERVICE_TYPE_FOR_CREATE['Refund']) {
                $referenceData[] = [
                    'refId' => $singleSupplier->supplierId,
                    'refModel' => Supplier::class,
                    'serviceCharge' => $singleSupplier->costOfSale,
                    'holidayId' => $singleSupplier->id,
                    'refundRequestDate' => $holiday->refundRequestDate,
                    'isRefunded' => 0,
                ];
            }
        }

        $holidayRefundBatchData = [];
        // Customer Holiday refund data process
        foreach ($referenceData as $ref) {
            $holidayRefund = new HolidayRefund();
            if (!$holidayRefund->load($requestData) || !$holidayRefund->load(['HolidayRefund' => $ref]) || !$holidayRefund->validate()) {
                return ['error' => true, 'message' => 'Holiday Refund validation failed - ' . Utilities::processErrorMessages($holidayRefund->getErrors())];
            }
            $holidayRefundBatchData[] = $holidayRefund->getAttributes(null, ['id']);
        }

        // Holiday Refund batch insert process
        if (empty($holidayRefundBatchData)) {
            return ['error' => true, 'message' => 'Holiday Refund batch data process failed.'];
        }

        if (!$this->holidayRepository->batchStore('holiday_refund', array_keys($holidayRefundBatchData[0]), $holidayRefundBatchData)) {
            return ['error' => true, 'message' => 'Holiday Refund batch insert failed'];
        }

        return ['error' => false, 'message' => 'Holiday Refund process done.'];
    }

    private
    static function calculateNetProfit(mixed $quoteAmount, mixed $costOfSale)
    {
        return ($quoteAmount - $costOfSale);
    }

    public
    function findHoliday(string $uid, $withArray = []): ActiveRecord
    {
        return $this->holidayRepository->findOne(['uid' => $uid], Holiday::class, $withArray);
    }

    public
    function getCategories(): array
    {
        return ArrayHelper::map($this->holidayRepository->findCategories(), 'id', 'name');
    }

}