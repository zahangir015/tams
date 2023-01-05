<?php

namespace app\modules\hrm\services;

use app\modules\hrm\models\Designation;
use app\modules\hrm\models\Employee;
use app\modules\hrm\repositories\HrmConfigurationRepository;
use yii\db\ActiveRecord;

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

    public function findModel(array $queryArray, string $model, array $withArray): ActiveRecord
    {
        return $this->hrmConfigurationRepository->findOne($queryArray, $model, $withArray);
    }

    public function getDesignationList(array $queryArray): array
    {
        $designationList = self::getAll($queryArray, Designation::class, [], true);
        $designationDataArray = [];
        foreach ($designationList as $value) {
            $designationDataArray[] = ['id' => $value['id'], 'name' => $value['name']];
        }

        return $designationDataArray;
    }

    public function getEmployeeList(array $queryArray, array $subQueryArray): array
    {
        $employeeList = $this->hrmConfigurationRepository->getEmployeeListByDepartment($queryArray, $subQueryArray);
        $employeeDataArray = [];
        foreach ($employeeList as $value) {
            $employeeDataArray[] = ['id' => $value['id'], 'name' => $value['firstName'].' '.$value['lastName']];
        }

        return $employeeDataArray;
    }
}