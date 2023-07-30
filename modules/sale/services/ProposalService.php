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
use app\modules\sale\models\RoomDetail;
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

    public function storeFlightProposal(array $requestData): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Flight Data process
            $flightProposal = new FlightProposal();
            if (!$flightProposal->load($requestData)) {
                throw new Exception('Flight proposal loading failed - ' . Utilities::processErrorMessages($flightProposal->getErrors()));
            }
            $flightProposal->agencyId = Yii::$app->user->identity->agencyId;
            $flightProposal = $this->proposalRepository->store($flightProposal);
            if ($flightProposal->hasErrors()) {
                throw new Exception('Flight proposal creation failed - ' . Utilities::processErrorMessages($flightProposal->getErrors()));
            }

            // Flight proposal itinerary process
            $flightProposalItineraryData = [];
            foreach ($requestData['FlightProposalItinerary'] as $itinerary) {
                $flightProposalItinerary = new FlightProposalItinerary();
                $flightProposalItinerary->load(['HotelSupplier' => $itinerary]);
                $flightProposalItinerary->flightProposalId = $flightProposal->id;
                $flightProposalItinerary = $this->proposalRepository->store($flightProposalItinerary);
                if (!$flightProposalItinerary->validate()) {
                    throw new Exception('Itinerary creation failed - ' . Utilities::processErrorMessages($flightProposalItinerary->getErrors()));
                }
                $flightProposalItineraryData[] = $flightProposalItinerary->getAttributes();
            }

            if (empty($flightProposalItineraryData)) {
                throw new Exception('Itinerary Detail Batch Data can not be empty.');
            }

            if (!$this->proposalRepository->batchStore(FlightProposalItinerary::tableName(), array_keys($flightProposalItineraryData[0]), $flightProposalItineraryData)) {
                throw new Exception('Itinerary Details batch insert failed.');
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Flight Proposal  added successfully', 'model' => $flightProposal];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public
    function updateFlightProposal(array $requestData, FlightProposal $flightProposal): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Update flight proposal
            if (!$flightProposal->load($requestData)) {
                throw new Exception('Flight proposal data loading failed - ' . Utilities::processErrorMessages($flightProposal->getErrors()));
            }
            $flightProposal = $this->proposalRepository->store($flightProposal);
            if ($flightProposal->hasErrors()) {
                throw new Exception('Flight proposal update failed - ' . Utilities::processErrorMessages($flightProposal->getErrors()));
            }

            //Update flight proposal itinerary
            $updateFlightProposalItineraryResponse = $this->updateItinerary($flightProposal, $requestData['FlightProposalItinerary']);
            if ($updateFlightProposalItineraryResponse['error']) {
                throw new Exception($updateFlightProposalItineraryResponse['message']);
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Hotel updated successfully', 'model' => $flightProposal];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    private function updateItinerary(ActiveRecord $flightProposal, mixed $flightProposalItineraries): array
    {
        foreach ($flightProposalItineraries as $itinerary) {
            if (isset($itinerary['id'])) {
                $flightProposalItinerary = $this->proposalRepository->findOne(['id' => $itinerary['id']], FlightProposalItinerary::class, []);
            } else {
                $flightProposalItinerary = new FlightProposalItinerary();
                $flightProposalItinerary->flightProposalId = $flightProposal->id;
            }

            $flightProposalItinerary->load(['FlightProposalItinerary' => $itinerary]);
            $flightProposalItinerary = $this->proposalRepository->store($flightProposalItinerary);
            if ($flightProposalItinerary->hasErrors()) {
                return ['error' => true, 'message' => 'Flight Proposal Itinerary update failed - ' . Utilities::processErrorMessages($flightProposalItinerary->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'Flight Proposal Itinerary updated successfully'];
    }

    public function storeHotelProposal(array $requestData): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Flight Data process
            $hotelProposal = new HotelProposal();
            if (!$hotelProposal->load($requestData)) {
                throw new Exception('Hotel proposal loading failed - ' . Utilities::processErrorMessages($hotelProposal->getErrors()));
            }
            $hotelProposal->agencyId = Yii::$app->user->identity->agencyId;
            $hotelProposal = $this->proposalRepository->store($hotelProposal);
            if ($hotelProposal->hasErrors()) {
                throw new Exception('Hotel proposal creation failed - ' . Utilities::processErrorMessages($hotelProposal->getErrors()));
            }

            // Hotel proposal itinerary process
            $roomDetailData = [];
            foreach ($requestData['RoomDetail'] as $room) {
                $roomDetail = new RoomDetail();
                $roomDetail->load(['RoomDetail' => $room]);
                $roomDetail->hotelProposalId = $hotelProposal->id;
                if (!$roomDetail->validate()) {
                    throw new Exception('Itinerary creation failed - ' . Utilities::processErrorMessages($roomDetail->getErrors()));
                }
                $roomDetailData[] = $roomDetail->getAttributes();
            }

            if (empty($roomDetailData)) {
                throw new Exception('Itinerary Detail Batch Data can not be empty.');
            }

            if (!$this->proposalRepository->batchStore(RoomDetail::tableName(), array_keys($roomDetailData[0]), $roomDetailData)) {
                throw new Exception('Room Details batch insert failed.');
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Hotel Proposal added successfully.', 'model' => $hotelProposal];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public
    function updateHotelProposal(array $requestData, HotelProposal $hotelProposal): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Update hotel proposal
            if (!$hotelProposal->load($requestData)) {
                throw new Exception('Hotel proposal data loading failed - ' . Utilities::processErrorMessages($hotelProposal->getErrors()));
            }
            $hotelProposal = $this->proposalRepository->store($hotelProposal);
            if ($hotelProposal->hasErrors()) {
                throw new Exception('Hotel proposal update failed - ' . Utilities::processErrorMessages($hotelProposal->getErrors()));
            }

            //Update flight proposal itinerary
            $updateRoomDetailResponse = $this->updateRoomDetail($hotelProposal, $requestData['RoomDetail']);
            if ($updateRoomDetailResponse['error']) {
                throw new Exception($updateRoomDetailResponse['message']);
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Hotel updated successfully', 'model' => $hotelProposal];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    private function updateRoomDetail(ActiveRecord $hotelProposal, array $roomDetails): array
    {
        foreach ($roomDetails as $roomDetail) {
            if (isset($roomDetail['id'])) {
                $roomDetail = $this->proposalRepository->findOne(['id' => $roomDetail['id']], RoomDetail::class, []);
            } else {
                $roomDetail = new RoomDetail();
                $roomDetail->hotelProposalId = $hotelProposal->id;
            }

            $roomDetail->load(['RoomDetail' => $roomDetail]);
            $roomDetail = $this->proposalRepository->store($roomDetail);
            if ($roomDetail->hasErrors()) {
                return ['error' => true, 'message' => 'Hotel room update failed - ' . Utilities::processErrorMessages($roomDetail->getErrors())];
            }
        }

        return ['error' => false, 'message' => 'Hotel room updated successfully'];
    }

}