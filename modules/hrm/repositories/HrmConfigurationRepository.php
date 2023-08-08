<?php

namespace app\modules\hrm\repositories;

use app\components\GlobalConstant;
use app\modules\hrm\models\Employee;
use app\repository\ParentRepository;
use yii\db\ActiveRecord;

class HrmConfigurationRepository extends ParentRepository
{
    public function getEmployeeListByDepartment(array $queryArray, array $subQueryArray)
    {
        return Employee::find()->joinWith(['employeeDesignation' => function ($query) use ($subQueryArray) {
            $query->where($subQueryArray);
        }])->where($queryArray)->createCommand()->getRawSql();
    }

}