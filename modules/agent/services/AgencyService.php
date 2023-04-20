<?php

namespace app\modules\agent\services;

use app\components\GlobalConstant;
use app\components\Utilities;;
use app\modules\agent\repositories\AgencyRepository;
use Exception;
use Yii;
use yii\db\ActiveRecord;

class AgencyService
{
    private AgencyRepository $agencyRepository;

    public function __construct()
    {
        $this->agencyRepository = new AgencyRepository();
    }

    public function getAll(array $queryArray, string $model, array $withArray, bool $asArray, array $selectArray = [])
    {
        return $this->agencyRepository->findAll($queryArray, $model, $withArray, $asArray, $selectArray);
    }

    public function findModel(array $queryArray, string $model, array $withArray = []): ActiveRecord
    {
        return $this->agencyRepository->findOne($queryArray, $model, $withArray);
    }

    public function deleteModel(array $queryArray, string $class, array $withArray = []): ActiveRecord
    {
        $model = self::findModel($queryArray, $class, $withArray);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        return $this->agencyRepository->store($model);
    }
}