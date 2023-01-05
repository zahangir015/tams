<?php

namespace app\modules\hrm\repositories;

use app\components\GlobalConstant;
use app\modules\hrm\models\Employee;
use app\repository\ParentRepository;

class HrmConfigurationRepository extends ParentRepository
{
    public function findAll($queryArray, $model, $withArray, $asArray)
    {
        $query = $model::find();
        if (!empty($withArray)) {
            $query->with($withArray);
        }

        $query->where($queryArray);
        if ($asArray) {
            $query->asArray();
        }

        return $query->all();
    }

    public function getEmployeeListByDepartment(array $queryArray, array $subQueryArray)
    {
        return Employee::find()->with(['employeeDesignations' => function ($query) use ($queryArray, $subQueryArray) {
            $query->where($subQueryArray);
        }])->where($queryArray)->all();
    }
}