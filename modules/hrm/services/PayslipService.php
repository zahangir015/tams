<?php

namespace app\modules\hrm\services;

use app\modules\hrm\models\PayrollType;
use app\modules\hrm\repositories\PayslipRepository;
use yii\db\ActiveRecord;

class PayslipService
{
    private PayslipRepository $payslipRepository;

    public function __construct()
    {
        $this->payslipRepository = new PayslipRepository();
    }

    public function getAll(array $queryArray, string $model, array $withArray, bool $asArray)
    {
        return $this->payslipRepository->findAll($queryArray, $model, $withArray, $asArray);
    }

    public function findModel(array $queryArray, string $model, array $withArray = []): ActiveRecord
    {
        return $this->payslipRepository->findOne($queryArray, $model, $withArray);
    }

    public function deleteModel(array $queryArray, string $class, array $withArray = []): ActiveRecord
    {
        $model = self::findModel($queryArray, $class, $withArray);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        return $this->payslipRepository->store($model);
    }

    public function storePayrollType(PayrollType $model)
    {

    }
}