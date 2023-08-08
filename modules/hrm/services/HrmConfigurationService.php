<?php

namespace app\modules\hrm\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\DepartmentShift;
use app\modules\hrm\models\Designation;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\LeaveAllocation;
use app\modules\hrm\models\YearlyLeaveAllocation;
use app\modules\hrm\repositories\HrmConfigurationRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

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

    public function findModel(array $queryArray, string $model, array $withArray = []): ActiveRecord
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

    public function getShiftListByDepartment(array $queryArray): array
    {
        $shiftList = self::getAll($queryArray, DepartmentShift::class, ['shift'], true);
        $shiftDataArray = [];
        foreach ($shiftList as $value) {
            $shiftDataArray[] = ['id' => $value['shift']['id'], 'name' => $value['shift']['title']];
        }

        return $shiftDataArray;
    }

    public function getEmployeeList(array $queryArray, array $subQueryArray): array
    {
        $employeeList = $this->hrmConfigurationRepository->getEmployeeListByDepartment($queryArray, $subQueryArray);dd($employeeList);
        $employeeDataArray = [];
        foreach ($employeeList as $value) {
            $employeeDataArray[] = ['id' => $value['id'], 'name' => $value['firstName'] . ' ' . $value['lastName']];
        }

        return $employeeDataArray;
    }

    public function deleteModel(array $queryArray, string $class, array $withArray = []): ActiveRecord
    {
        $model = self::findModel($queryArray, $class, $withArray);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        return $this->hrmConfigurationRepository->store($model);
    }

    public function batchInsertYearlyAllocation(array $requestData): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $batchData = [];
            foreach ($requestData['YearlyLeaveAllocation'] as $singleData) {
                $model = new YearlyLeaveAllocation();
                if ($model->load(['YearlyLeaveAllocation' => $singleData]) && $model->validate()) {
                    $model->status = GlobalConstant::ACTIVE_STATUS;
                    $batchData[] = $model->getAttributes();
                } else {
                    throw new Exception('Yearly Leave allocation failed - ' . Utilities::processErrorMessages($model->getErrors()));
                }
            }
            // Yearly allocation batch data process
            if (empty($batchData)) {
                throw new Exception('Yearly allocation data is required.');
            }
            $yearlyAllocationResponse = $this->hrmConfigurationRepository->batchStore(YearlyLeaveAllocation::tableName(), array_keys($batchData[0]), $batchData);
            if (!$yearlyAllocationResponse) {
                throw new Exception('Yearly leave allocation failed.');
            }

            // Employee allocation batch data process
            $employees = $this->hrmConfigurationRepository->findAll(['status' => GlobalConstant::ACTIVE_STATUS], Employee::class, [], false, ['id', 'inProhibition']);
            $employeeLeaveAllocationBatchData = [];
            foreach ($employees as $singleEmployee) {
                foreach ($requestData['YearlyLeaveAllocation'] as $datum) {
                    $employeeLeaveAllocation = new LeaveAllocation();
                    $employeeLeaveAllocation->scenario = 'create';
                    $employeeLeaveAllocation->load(['LeaveAllocation' => $datum]);
                    $employeeLeaveAllocation->employeeId = $singleEmployee->id;
                    // Todo old employee leave allocation check
                    $employeeLeaveAllocation->totalDays = ($singleEmployee->inProhibition) ? 1 : $datum['numberOfDays'];
                    $employeeLeaveAllocation->availedDays = 0;
                    $employeeLeaveAllocation->remainingDays = $employeeLeaveAllocation->totalDays;
                    if (!$employeeLeaveAllocation->validate()) {
                        throw new Exception('Employee Leave allocation failed - ' . Utilities::processErrorMessages($model->getErrors()));
                    }
                    $employeeLeaveAllocationBatchData[] = $employeeLeaveAllocation->getAttributes();
                }
            }

            if (empty($employeeLeaveAllocationBatchData)) {
                throw new Exception('Employee allocation data is required.');
            }
            $employeeLeaveAllocationResponse = $this->hrmConfigurationRepository->batchStore(LeaveAllocation::tableName(), array_keys($employeeLeaveAllocationBatchData[0]), $employeeLeaveAllocationBatchData);
            if (!$employeeLeaveAllocationResponse) {
                throw new Exception('Employee leave allocation failed.');
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Successfully stored'];
        } catch (Exception $exception) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $exception->getMessage()];
        }
    }

    public function updateYearlyAllocation(ActiveRecord $model): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            //Yearly Allocation update
            $yearlyLeaveAllocation = $this->hrmConfigurationRepository->store($model);
            if ($yearlyLeaveAllocation->hasErrors()) {
                return ['error' => true, 'message' => 'Yearly Allocation update failed - ' . Utilities::processErrorMessages($yearlyLeaveAllocation->getErrors())];
            }

            // Employee allocation process
            $employeeAllocationUpdateResponse = $this->hrmConfigurationRepository->update(['totalDays' => $yearlyLeaveAllocation->numberOfDays, 'remainingDays' => 'totalDays-availedDays'], ['and', ['year' => $yearlyLeaveAllocation->year], ['status' => GlobalConstant::ACTIVE_STATUS], ['leaveTypeId' => $yearlyLeaveAllocation->leaveTypeId]], LeaveAllocation::class);
            if (!$employeeAllocationUpdateResponse) {
                return ['error' => true, 'message' => 'Employee Leave Allocation update failed.'];
            }

            $dbTransaction->commit();
            return ['error' => false, 'message' => 'Successfully stored'];
        } catch (Exception $exception) {
            $dbTransaction->rollBack();
            return ['error' => true, 'message' => $exception->getMessage()];
        }
    }

}