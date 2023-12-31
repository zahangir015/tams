<?php

namespace app\modules\hrm\repositories;

use app\components\GlobalConstant;
use app\modules\hrm\models\Employee;
use app\repository\ParentRepository;
use Yii;

class EmployeeRepository extends ParentRepository
{
    public function employeeQuery(mixed $query)
    {
        return Employee::find()
            ->select(['id', 'firstName', 'lastName', 'officialId', 'OfficialEmail'])
            ->where(['like', 'firstName', $query])
            ->orWhere(['like', 'lastName', $query])
            ->orWhere(['like', 'officialId', $query])
            ->orWhere(['like', 'OfficialEmail', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere(['agencyId' => Yii::$app->user->identity->agencyId])
            ->all();
    }
}