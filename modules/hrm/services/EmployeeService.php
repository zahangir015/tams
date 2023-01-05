<?php

namespace app\modules\hrm\services;


use app\components\Helper;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\EmployeeDesignation;
use app\modules\hrm\repositories\EmployeeRepository;
use Exception;
use Yii;

class EmployeeService
{
    private EmployeeRepository $employeeRepository;

    public function __construct()
    {
        $this->employeeRepository = new EmployeeRepository();
    }

    public function storeEmployee(array $requestData, Employee $employee, EmployeeDesignation $employeeDesignation): bool
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if (!empty($requestData['Employee']) || !empty($requestData['EmployeeDesignation'])) {
                if ($employee->load($requestData)) {
                    $employee = $this->employeeRepository->store($employee);
                    if ($employee->hasErrors()) {
                        throw new Exception('Employee creation failed - ' . Helper::processErrorMessages($employee->getErrors()));
                    }

                    // Hotel Supplier data process
                    if ($employeeDesignation->load($requestData)) {
                        $employeeDesignation->employeeId = $employee->id;
                        $employeeDesignation->startDate = $employee->joiningDate;
                        $employeeDesignation = $this->employeeRepository->store($employeeDesignation);
                        if ($employeeDesignation->hasErrors()) {
                            throw new Exception('Employee Designation creation failed - ' . Helper::processErrorMessages($employeeDesignation->getErrors()));
                        }
                    } else {
                        throw new Exception('Employee Designation data loading failed - ' . Helper::processErrorMessages($employee->getErrors()));
                    }
                } else {
                    throw new Exception('Employee data loading failed - ' . Helper::processErrorMessages($employee->getErrors()));
                }

                // Succeefully stored
                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Ticket added successfully');
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

    private static function employeeDesignationProcess(Employee $employee, array $requestData)
    {

    }
}