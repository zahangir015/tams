<?php

namespace app\modules\hrm\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\EmployeePayroll;
use app\modules\hrm\models\EmployeePayrollTypeDetail;
use app\modules\hrm\models\PayrollType;
use app\modules\hrm\repositories\PayslipRepository;
use Exception;
use Yii;
use yii\db\ActiveRecord;

class PayslipService
{
    private PayslipRepository $payslipRepository;

    public function __construct()
    {
        $this->payslipRepository = new PayslipRepository();
    }

    public function getAll(array $queryArray, string $model, array $withArray, bool $asArray, array $selectArray = [])
    {
        return $this->payslipRepository->findAll($queryArray, $model, $withArray, $asArray, $selectArray);
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
    public function storeEmployeePayroll(EmployeePayroll $employeePayroll, array $requestData): array
    {
        if (!isset($requestData['EmployeePayroll'])) {
            return ['error' => true, 'message' => 'Employee Payroll data is required.'];
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Store employeePayroll data
            $employeePayroll->load($requestData);
            $employeePayroll = $this->payslipRepository->store($employeePayroll);
            if ($employeePayroll->hasErrors()) {
                throw new Exception('Employee payroll creation failed - ' . Utilities::processErrorMessages($employeePayroll->getErrors()));
            }

            // Employee Payroll Type Detail process
            $employeePayrollTpeDetails = $requestData['EmployeePayrollTypeDetail'];
            if (!isset($employeePayrollTpeDetails)) {
                throw new Exception('Employee payroll type detail data is required.');
            }

            foreach ($employeePayrollTpeDetails as $singleType) {
                $employeePayrollTypeDetail = new EmployeePayrollTypeDetail();
                $employeePayrollTypeDetail->load(['EmployeePayrollTypeDetail' => $singleType]);
                $employeePayrollTypeDetail->employeePayrollId = $employeePayroll->id;
                $employeePayrollTypeDetail = $this->payslipRepository->store($employeePayrollTypeDetail);
                if ($employeePayrollTypeDetail->hasErrors()) {
                    throw new Exception('Employee payroll type detail creation failed - ' . Utilities::processErrorMessages($employeePayrollTypeDetail->getErrors()));
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Employee payroll is successfully created.', 'data' => $employeePayroll];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
    public function updateEmployeePayroll(?ActiveRecord $employeePayroll, mixed $requestData): array
    {
        if (!isset($requestData['EmployeePayroll'])) {
            return ['error' => true, 'message' => 'Employee Payroll data is required.'];
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Store employeePayroll data
            $employeePayroll->load($requestData);
            $employeePayroll = $this->payslipRepository->store($employeePayroll);
            if ($employeePayroll->hasErrors()) {
                throw new Exception('Employee payroll creation failed - ' . Utilities::processErrorMessages($employeePayroll->getErrors()));
            }

            // Employee Payroll Type Detail process
            $employeePayrollTpeDetails = $requestData['EmployeePayrollTypeDetail'];
            if (!isset($employeePayrollTpeDetails)) {
                throw new Exception('Employee payroll type detail data is required.');
            }

            // Payroll type update
            $payrollTypeResponse = $this->payslipRepository->deleteAll(EmployeePayrollTypeDetail::class, ['employeePayrollId' => $employeePayroll->id]);
            if (!$payrollTypeResponse){
                throw new Exception('Employee payroll type detail update failed.');
            }
            foreach ($employeePayrollTpeDetails as $singleType) {
                $employeePayrollTypeDetail = new EmployeePayrollTypeDetail();
                $employeePayrollTypeDetail->load(['EmployeePayrollTypeDetail' => $singleType]);
                $employeePayrollTypeDetail->employeePayrollId = $employeePayroll->id;
                $employeePayrollTypeDetail = $this->payslipRepository->store($employeePayrollTypeDetail);
                if ($employeePayrollTypeDetail->hasErrors()) {
                    throw new Exception('Employee payroll type detail update failed - ' . Utilities::processErrorMessages($employeePayrollTypeDetail->getErrors()));
                }
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Employee payroll is successfully updated.', 'data' => $employeePayroll];
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}