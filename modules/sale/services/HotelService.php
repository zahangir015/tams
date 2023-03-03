<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\models\Invoice;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\LedgerService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\hotel\HotelCategory;
use app\modules\sale\models\hotel\HotelRefund;
use app\modules\sale\models\hotel\HotelSupplier;
use app\modules\sale\models\Supplier;
use app\modules\sale\repositories\HotelRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class HotelService
{
    private HotelRepository $hotelRepository;
    private InvoiceService $invoiceService;

    public function __construct()
    {
        $this->hotelRepository = new HotelRepository();
        $this->invoiceService = new InvoiceService();
    }

    private static function calculateNetProfit(mixed $quoteAmount, mixed $costOfSale)
    {
        return ($quoteAmount - $costOfSale);
    }

    public function findHotel(string $uid, $withArray = []): ActiveRecord
    {
        return $this->hotelRepository->findOne(['uid' => $uid], Hotel::class, $withArray);
    }

    public function storeHotel(array $requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (empty($requestData['Hotel']) || empty($requestData['HotelSupplier'])) {
                throw new Exception('Hotel and supplier data can not be empty.');
            }
            // Hotel Data process
            $customer = Customer::findOne(['id' => $requestData['Hotel']['customerId']]);
            $hotel = new Hotel();
            if (!$hotel->load($requestData)) {
                throw new Exception('Hotel data loading failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
            }
            $hotel->type = ServiceConstant::TYPE['New'];
            $hotel->customerCategory = $customer->category;
            $hotel = $this->hotelRepository->store($hotel);
            if ($hotel->hasErrors()) {
                throw new Exception('Hotel create failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
            }

            // Hotel Supplier data process
            foreach ($requestData['HotelSupplier'] as $singleSupplierArray) {
                $hotelSupplier = new HotelSupplier();
                $hotelSupplier->load(['HotelSupplier' => $singleSupplierArray]);
                $hotelSupplier->hotelId = $hotel->id;
                $hotelSupplier = $this->hotelRepository->store($hotelSupplier);
                if ($hotelSupplier->hasErrors()) {
                    throw new Exception('Hotel Supplier refund creation failed - ' . Utilities::processErrorMessages($hotelSupplier->getErrors()));
                }
            }

            // Invoice process and create
            if (isset($requestData['invoice'])) {
                // Invoice details data process
                $services[] = [
                    'refId' => $hotel->id,
                    'refModel' => Hotel::class,
                    'dueAmount' => $hotel->quoteAmount,
                    'paidAmount' => 0,
                ];

                // Auto Invoice process
                $autoInvoiceCreateResponse = $this->invoiceService->autoInvoice($customer->id, $services);
                if ($autoInvoiceCreateResponse['error']) {
                    throw new Exception('Auto Invoice creation failed - ' . $autoInvoiceCreateResponse['message']);
                }
            }

            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Hotel added successfully');
            return true;
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage());
            return false;
        }
    }

    public function refundHotel(array $requestData, Hotel $motherHotel): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Hotel and supplier data can not be empty
            if (empty($requestData['Hotel']) || empty($requestData['HotelSupplier'])) {
                throw new Exception('Hotel and supplier data can not be empty.');
            }

            $customer = Customer::findOne(['id' => $requestData['Hotel']['customerId']]);
            $hotel = new Hotel();
            if (!$hotel->load($requestData)) {
                throw new Exception('Hotel data loading failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
            }
            $hotel->customerCategory = $customer->category;
            $hotel->invoiceId = $motherHotel->invoiceId;
            $hotel = $this->hotelRepository->store($hotel);
            if ($hotel->hasErrors()) {
                throw new Exception('Hotel refund creation failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
            }

            // Mother Hotel update
            $motherHotel->type = ServiceConstant::TICKET_TYPE_FOR_REFUND['Refund Requested'];
            $motherHotel->refundRequestDate = $hotel->refundRequestDate;
            $motherHotel = $this->hotelRepository->store($motherHotel);
            if ($motherHotel->hasErrors()) {
                throw new Exception('Mother hotel update failed - ' . Utilities::processErrorMessages($motherHotel->getErrors()));
            }

            // Hotel Supplier data process
            foreach ($requestData['HotelSupplier'] as $singleSupplierArray) {
                $hotelSupplier = new HotelSupplier();
                $hotelSupplier->load(['HotelSupplier' => $singleSupplierArray]);
                $hotelSupplier->hotelId = $hotel->id;
                $hotelSupplier = $this->hotelRepository->store($hotelSupplier);
                if ($hotelSupplier->hasErrors()) {
                    throw new Exception('Hotel Supplier refund creation failed - ' . Utilities::processErrorMessages($hotelSupplier->getErrors()));
                }
            }

            // Create refund for customer and supplier
            $refundDataProcessResponse = self::processRefundModelData($hotel, $requestData);
            if ($refundDataProcessResponse['error']) {
                throw new Exception('Hotel refund creation failed - ' . $refundDataProcessResponse['message']);
            }

            // Invoice process
            if ($motherHotel->invoiceId) {
                // Invoice details data process
                $service = [
                    'invoiceId' => $motherHotel->invoiceId ?? null,
                    'refId' => $hotel->id,
                    'refModel' => Hotel::class,
                    'dueAmount' => ($hotel->quoteAmount - $hotel->receivedAmount),
                    'paidAmount' => $hotel->receivedAmount,
                    'motherId' => $motherHotel->id,
                ];

                $autoInvoiceCreateResponse = $this->invoiceService->autoInvoiceForRefund($motherHotel->invoice, $service, Yii::$app->user);
                if ($autoInvoiceCreateResponse['error']) {
                    throw new Exception('Auto Invoice detail creation failed - ' . $autoInvoiceCreateResponse['message']);
                }
            }

            $dbTransaction->commit();
            Yii::$app->session->setFlash('success', 'Hotel added successfully');
            return true;
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public
    function updateHotel(array $requestData, Hotel $hotel): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Hotel and supplier data can not be empty
            if (empty($requestData['Hotel']) || empty($requestData['HotelSupplier'])) {
                throw new Exception('Hotel and supplier data required.');
            }

            $oldQuoteAmount = $hotel->quoteAmount;

            // Update Hotel
            if ($hotel->load($requestData)){
                throw new Exception('Hotel data loading failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
            }
            $hotel->netProfit = self::calculateNetProfit($hotel->quoteAmount, $hotel->costOfSale);
            $hotel->paymentStatus = (new InvoiceService())->checkAndDetectPaymentStatus($hotel->quoteAmount, $hotel->receivedAmount);
            $hotel = $this->hotelRepository->store($hotel);
            if ($hotel->hasErrors()) {
                throw new Exception('Hotel update failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
            }

            //Update Hotel Supplier Entity
            $updateHotelSupplierResponse = $this->updateHotelSupplier($hotel, $requestData['HotelSupplier']);
            if ($updateHotelSupplierResponse['error']) {
                throw new Exception($updateHotelSupplierResponse['message']);
            }

            // If invoice created and quote updated then process related data
            if (isset($hotel->invoice) && ($oldQuoteAmount != $hotel->quoteAmount)) {
                //Update Invoice Entity
                $services[] = [
                    'refId' => $hotel->id,
                    'refModel' => Hotel::class,
                    'due' => ($hotel->quoteAmount - $hotel->receivedAmount),
                    'amount' => $hotel->receivedAmount
                ];

                $serviceRelatedDataProcessResponse = SaleService::updatedServiceRelatedData($hotel, $services);
                if ($serviceRelatedDataProcessResponse['error']) {
                    throw new Exception($serviceRelatedDataProcessResponse['message']);
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Hotel updated successfully', 'model' => $hotel];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    private function updateHotelSupplier(ActiveRecord $hotel, mixed $hotelSuppliers): array
    {
        foreach ($hotelSuppliers as $supplier) {
            if (isset($supplier['id'])) {
                $hotelSupplier = $this->hotelRepository->findOne(['id' => $supplier['id']], HotelSupplier::class, []);
            } else {
                $hotelSupplier = new HotelSupplier();
                $hotelSupplier->hotelId = $hotel->id;
                $hotelSupplier->status = GlobalConstant::ACTIVE_STATUS;
                $hotelSupplier->paymentStatus = ServiceConstant::PAYMENT_STATUS['Due'];
            }
            $hotelSupplier->load(['HotelSupplier' => $supplier]);
            $hotelSupplier->type = $supplier['type'] ?? ServiceConstant::TYPE['New'];
            $hotelSupplier = $this->hotelRepository->store($hotelSupplier);
            if ($hotelSupplier->hasErrors()) {
                return ['error' => true, 'message' => 'Hotel Supplier update failed - ' . Utilities::processErrorMessages($hotelSupplier->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'Hotel Supplier updated successfully'];
    }

    public function updateRefundHotel(array $requestData, ActiveRecord $hotel): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Hotel and supplier data can not be empty
            if (empty($requestData['Hotel']) || empty($requestData['HotelSupplier'])) {
                throw new Exception('Hotel and supplier data is required.');
            }
            $oldQuoteAmount = $hotel->quoteAmount;

            // Hotel data processing
            $customer = Customer::findOne(['id' => $requestData['Hotel']['customerId']]);
            if (!$hotel->load($requestData)) {
                throw new Exception('Hotel data loading failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
            }

            $hotel = $this->hotelRepository->store($hotel);
            if ($hotel->hasErrors()) {
                throw new Exception('Hotel update failed - ' . Utilities::processErrorMessages($hotel->getErrors()));
            }

            // Customer Refund data update
            $customerHotelRefund = HotelRefund::find()->where(['refId' => $customer->id, 'refModel' => 'app\\modules\\sale\\models\\Customer', 'hotelId' => $hotel->id])->one();
            if (!$customerHotelRefund) {
                throw new Exception('Customer Hotel refund not found.');
            }
            $customerHotelRefund->load($requestData);
            $customerHotelRefund->serviceCharge = $hotel->quoteAmount;
            $customerHotelRefund->refundRequestDate = $hotel->refundRequestDate;
            $customerHotelRefund = $this->hotelRepository->store($customerHotelRefund);
            if ($customerHotelRefund->hasErrors()) {
                throw new Exception('Customer hotel refund update failed - ' . Utilities::processErrorMessages($customerHotelRefund->getErrors()));
            }

            // Mother Hotel update
            $motherHotel = $this->hotelRepository->findOne(['id' => $hotel->motherId], Hotel::class);
            $motherHotel->refundRequestDate = $hotel->refundRequestDate;
            $motherHotel = $this->hotelRepository->store($motherHotel);
            if ($motherHotel->hasErrors()) {
                throw new Exception('Mother hotel update failed - ' . Utilities::processErrorMessages($motherHotel->getErrors()));
            }

            // Hotel Supplier data process
            foreach ($requestData['HotelSupplier'] as $singleSupplierArray) {
                $hotelSupplier = HotelSupplier::findOne(['id' => $singleSupplierArray['id']]);
                $hotelSupplier->load(['HotelSupplier' => $singleSupplierArray]);
                $hotelSupplier->hotelId = $hotel->id;
                $hotelSupplier->refundRequestDate = $hotel->refundRequestDate;
                $hotelSupplier = $this->hotelRepository->store($hotelSupplier);
                if ($hotelSupplier->hasErrors()) {
                    throw new Exception('Hotel Supplier refund creation failed - ' . Utilities::processErrorMessages($hotelSupplier->getErrors()));
                }

                // Supplier refund data update
                $supplierHotelRefund = HotelRefund::find()->where(['refId' => $hotelSupplier->supplierId, 'refModel' => 'app\\modules\\sale\\models\\Supplier', 'hotelId' => $hotel->id])->one();
                if (!$supplierHotelRefund) {
                    throw new Exception('Supplier Hotel refund not found.');
                }
                $supplierHotelRefund->load($requestData);
                $supplierHotelRefund->serviceCharge = $hotelSupplier->costOfSale;
                $supplierHotelRefund->refundRequestDate = $hotelSupplier->refundRequestDate;
                $supplierHotelRefund = $this->hotelRepository->store($supplierHotelRefund);
                if ($supplierHotelRefund->hasErrors()) {
                    throw new Exception('Supplier hotel refund update failed - ' . Utilities::processErrorMessages($supplierHotelRefund->getErrors()));
                }
            }

            // If invoice created and quote updated then process related data
            if (isset($hotel->invoice) && ($oldQuoteAmount != $hotel->quoteAmount)) {
                //Update Invoice Entity
                $services[] = [
                    'refId' => $hotel->id,
                    'refModel' => Hotel::class,
                    'dueAmount' => ($hotel->quoteAmount - $hotel->receivedAmount),
                    'paidAmount' => $hotel->receivedAmount
                ];

                $serviceRelatedDataProcessResponse = SaleService::updatedServiceRelatedData($hotel, $services);
                if ($serviceRelatedDataProcessResponse['error']) {
                    throw new Exception($serviceRelatedDataProcessResponse['message']);
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Refund Hotel updated successfully.'];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => false, 'message' => $e->getMessage()];
        }
    }

    private
    function hotelSupplierProcess(ActiveRecord $hotel, mixed $hotelSuppliers): array
    {
        foreach ($hotelSuppliers as $singleSupplierArray) {
            $hotelSupplier = new HotelSupplier();
            $hotelSupplier->load(['HotelSupplier' => $singleSupplierArray]);
            $hotelSupplier->hotelId = $hotel->id;
            $hotelSupplier = $this->hotelRepository->store($hotelSupplier);
            if ($hotelSupplier->hasErrors()) {
                return ['error' => true, 'message' => 'Hotel Supplier creation failed - ' . Utilities::processErrorMessages($hotelSupplier->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'Hotel Supplier added successfully.'];
    }

    private function processRefundModelData(ActiveRecord $hotel, array $requestData): array
    {
        $referenceData = [
            [
                'refId' => $hotel->customerId,
                'refModel' => Customer::class,
                'serviceCharge' => $hotel->quoteAmount,
                'hotelId' => $hotel->id,
                'refundRequestDate' => $hotel->refundRequestDate,
                'isRefunded' => 0,
            ],
        ];

        foreach ($hotel->hotelSuppliers as $singleSupplier) {
            if ($singleSupplier->type == ServiceConstant::SERVICE_TYPE_FOR_CREATE['Refund']) {
                $referenceData[] = [
                    'refId' => $singleSupplier->supplierId,
                    'refModel' => Supplier::class,
                    'serviceCharge' => $singleSupplier->costOfSale,
                    'hotelId' => $singleSupplier->id,
                    'refundRequestDate' => $hotel->refundRequestDate,
                    'isRefunded' => 0,
                ];
            }
        }

        $hotelRefundBatchData = [];
        // Customer Hotel refund data process
        foreach ($referenceData as $ref) {
            $hotelRefund = new HotelRefund();
            $hotelRefund->load($requestData);
            $hotelRefund->load(['HotelRefund' => $ref]);
            if (!$hotelRefund->validate()) {
                return ['error' => true, 'message' => 'Hotel Refund validation failed - ' . Utilities::processErrorMessages($hotelRefund->getErrors())];
            }
            $hotelRefundBatchData[] = $hotelRefund->getAttributes(null, ['id']);
        }

        // Hotel Refund batch insert process
        if (empty($hotelRefundBatchData)) {
            return ['error' => true, 'message' => 'Hotel Refund batch data process failed.'];
        }

        if (!$this->hotelRepository->batchStore('hotel_refund', array_keys($hotelRefundBatchData[0]), $hotelRefundBatchData)) {
            return ['error' => true, 'message' => 'Hotel Refund batch insert failed'];
        }

        return ['error' => false, 'message' => 'Hotel Refund process done.'];
    }
}