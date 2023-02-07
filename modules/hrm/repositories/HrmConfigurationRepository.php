<?php

namespace app\modules\hrm\repositories;

use app\components\GlobalConstant;
use app\modules\hrm\models\Employee;
use app\repository\ParentRepository;

class HrmConfigurationRepository extends ParentRepository
{
    public function getEmployeeListByDepartment(array $queryArray, array $subQueryArray)
    {
        return Employee::find()->with(['employeeDesignations' => function ($query) use ($queryArray, $subQueryArray) {
            $query->where($subQueryArray);
        }])->where($queryArray)->all();
    }
}