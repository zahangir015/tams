<?php

namespace app\modules\sale\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use app\modules\sale\models\FlightProposal;
use app\modules\sale\models\FlightProposalItinerary;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\HotelCategory;
use app\modules\sale\models\HotelProposal;
use app\modules\sale\repositories\HotelRepository;
use app\modules\sale\repositories\ProposalRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class ProposalService
{
    private ProposalRepository $proposalRepository;

    public function __construct()
    {
        $this->proposalRepository = new ProposalRepository();
    }

    public function findHotelProposal(string $uid, $withArray = []): ActiveRecord
    {
        return $this->proposalRepository->findOne(['uid' => $uid], HotelProposal::class, $withArray);
    }

    public function findFlightProposal(string $uid, $withArray = []): ActiveRecord
    {
        return $this->proposalRepository->findOne(['uid' => $uid], FlightProposal::class, $withArray);
    }

    public function storeFlightProposal(array $requestData): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Flight Data process
            $flightProposal = new FlightProposal();
            if (!$flightProposal->load($requestData)){
                throw new Exception('Flight proposal loading failed - ' . Utilities::processErrorMessages($flightProposal->getErrors()));
            }
            $flightProposal = $this->proposalRepository->store($flightProposal);
            if ($flightProposal->hasErrors()) {
                throw new Exception('Flight proposal creation failed - ' . Utilities::processErrorMessages($flightProposal->getErrors()));
            }

            // Flight proposal itinerary process
            foreach ($requestData['FlightProposalItinerary'] as $itinerary) {
                $flightProposalItinerary = new FlightProposalItinerary();
                $flightProposalItinerary->load(['HotelSupplier' => $itinerary]);
                $flightProposalItinerary->flightProposalId = $flightProposal->id;
                $flightProposalItinerary = $this->proposalRepository->store($flightProposalItinerary);
                if ($flightProposalItinerary->hasErrors()) {
                    throw new Exception('Itinerary creation failed - ' . Utilities::processErrorMessages($flightProposalItinerary->getErrors()));
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

    public
    function updateFlightProposal(array $requestData, FlightProposal $hotel): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Update Hotel
            if ($hotel->load($requestData)) {
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
}