<?php

namespace app\modules\sale\repositories;

use app\components\GlobalConstant;
use app\modules\sale\models\FlightProposal;
use app\modules\sale\models\HotelProposal;
use app\modules\sale\models\visa\Visa;
use app\repository\ParentRepository;

class ProposalRepository extends ParentRepository
{
    public function findAllFlightProposal(string $query): array
    {
        return FlightProposal::find()
            ->select(['id', 'identificationNumber'])
            ->where(['like', 'identificationNumber', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }

    public function findAllHotelProposal(string $query): array
    {
        return HotelProposal::find()
            ->select(['id', 'identificationNumber'])
            ->where(['like', 'identificationNumber', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}