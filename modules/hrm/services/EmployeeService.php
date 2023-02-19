<?php

namespace app\modules\hrm\services;


use app\components\Utilities;
use app\modules\admin\models\form\Signup;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\EmployeeDesignation;
use app\modules\hrm\repositories\EmployeeRepository;
use Exception;
use Yii;
use yii\db\ActiveRecord;

class EmployeeService
{
    private EmployeeRepository $employeeRepository;

    public function __construct()
    {
        $this->employeeRepository = new EmployeeRepository();
    }

    public function storeEmployee(array $requestData, Employee $employee, EmployeeDesignation $employeeDesignation, Signup $signupModel): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // User create
            $user = null;
            if (isset($requestData['Signup']) && !empty($requestData['Signup'])) {
                if ($signupModel->load($requestData)) {
                    $user = $signupModel->signup();
                    if (is_null($user)) {
                        throw new Exception('User creation failed.');
                    }
                }
            }
            // Employee create
            if (!empty($requestData['Employee']) || !empty($requestData['EmployeeDesignation'])) {
                if ($employee->load($requestData)) {
                    $employee->userId = !is_null($user) ? $user->id : null;
                    $employee = $this->employeeRepository->store($employee);
                    if ($employee->hasErrors()) {
                        throw new Exception('Employee creation failed - ' . Utilities::processErrorMessages($employee->getErrors()));
                    }

                    // Hotel Supplier data process
                    if ($employeeDesignation->load($requestData)) {
                        $employeeDesignation->employeeId = $employee->id;
                        $employeeDesignation->startDate = $employee->joiningDate;
                        $employeeDesignation = $this->employeeRepository->store($employeeDesignation);
                        if ($employeeDesignation->hasErrors()) {
                            throw new Exception('Employee Designation creation failed - ' . Utilities::processErrorMessages($employeeDesignation->getErrors()));
                        }
                    } else {
                        throw new Exception('Employee Designation data loading failed - ' . Utilities::processErrorMessages($employee->getErrors()));
                    }
                } else {
                    throw new Exception('Employee data loading failed - ' . Utilities::processErrorMessages($employee->getErrors()));
                }

                // Succeefully stored
                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Employee profile created successfully');
                return true;
            }
            // Ticket and supplier data can not be empty
            throw new Exception('Employee and Designation data can not be empty.');

        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }

    public function updateEmployee(mixed $requestData, ActiveRecord $employee, EmployeeDesignation $employeeDesignation): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Employee']) || !empty($requestData['EmployeeDesignation'])) {
                if ($employee->load($requestData)) {
                    $employee = $this->employeeRepository->store($employee);
                    if ($employee->hasErrors()) {
                        throw new Exception('Employee update failed - ' . Utilities::processErrorMessages($employee->getErrors()));
                    }

                    // Hotel Supplier data process
                    if ($employeeDesignation->load($requestData)) {
                        $employeeDesignation->startDate = $employee->joiningDate;
                        $employeeDesignation = $this->employeeRepository->store($employeeDesignation);
                        if ($employeeDesignation->hasErrors()) {
                            throw new Exception('Employee Designation creation failed - ' . Utilities::processErrorMessages($employeeDesignation->getErrors()));
                        }
                    } else {
                        throw new Exception('Employee Designation data loading failed - ' . Utilities::processErrorMessages($employee->getErrors()));
                    }
                } else {
                    throw new Exception('Employee data loading failed - ' . Utilities::processErrorMessages($employee->getErrors()));
                }

                // Succeefully stored
                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Employee profile updated successfully');
                return true;
            }
            // Employee and Designation data can not be empty
            throw new Exception('Employee and Designation data can not be empty.');
            return false;
        } catch (Exception $e) {
            $dbTransaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage() . ' - in file - ' . $e->getFile() . ' - in line -' . $e->getLine());
            return false;
        }
    }
}