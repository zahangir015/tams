<?php
namespace app\modules\hrm\services;

use app\modules\hrm\repositories\HrmConfigurationRepository;

class HrmConfigurationService
{
    private HrmConfigurationRepository $hrmConfigurationRepository;

    public function __construct()
    {
        $this->hrmConfigurationRepository = new HrmConfigurationRepository();
    }

    public function getAll(array $queryArray, string $model, array $withArray, bool $asArray)
    {
        return $this->hrmConfigurationRepository->findAll($queryArray, $model, $withArray, $asArray);
    }
}