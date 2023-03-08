<?php

namespace app\modules\hrm\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\Attendance;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\LeaveAllocation;
use app\modules\hrm\models\LeaveApplication;
use app\modules\hrm\models\LeaveApprovalHistory;
use app\modules\hrm\models\LeaveApprovalPolicy;
use app\modules\hrm\repositories\AttendanceRepository;
use app\modules\sale\models\Customer;
use yii\base\Exception;
use yii\db\ActiveRecord;

class AttendanceService
{
    private AttendanceRepository $attendanceRepository;

    public function __construct()
    {
        $this->attendanceRepository = new AttendanceRepository();
    }

    public function getAll(array $queryArray, string $model, array $withArray, bool $asArray)
    {
        return $this->attendanceRepository->findAll($queryArray, $model, $withArray, $asArray);
    }

    public function findModel(array $queryArray, string $model, array $withArray = []): ActiveRecord
    {
        return $this->attendanceRepository->findOne($queryArray, $model, $withArray);
    }

    public function deleteModel(array $queryArray, string $class, array $withArray = []): ActiveRecord
    {
        $model = self::findModel($queryArray, $class, $withArray);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        return $this->attendanceRepository->store($model);
    }

    /*public function storeAttendance(Attendance $model): Attendance
    {

    }*/

    public function storeLeave(LeaveApplication $leaveApplication, array $requestData, array $relatedData): array
    {
        $dbTransaction = \Yii::$app->db->beginTransaction();
        try {
            // Application loading
            if (!$leaveApplication->load($requestData)) {
                throw new Exception('Leave application loading failed - ' . Utilities::processErrorMessages($leaveApplication->getErrors()));
            }

            // Store application
            $leaveApplication = $this->attendanceRepository->store($leaveApplication);
            if ($leaveApplication->hasErrors()) {
                throw new Exception('Leave application storing failed - ' . Utilities::processErrorMessages($leaveApplication->getErrors()));
            }

            // Approval History store
            foreach ($relatedData['approvalPolicies'] as $approvalPolicy) {
                $leaveApprovalHistory = new LeaveApprovalHistory();
                $leaveApplication->load($approvalPolicy->getAttributes(['employeeId', 'requestedTo', 'approvalLevel']));
            }

            $dbTransaction->commit();
            return [
                'error' => false,
                'message' => 'Leave application created successfully.'
            ];
        } catch (Exception $exception) {
            $dbTransaction->rollBack();
            return [
                'error' => true,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function applicationValidityCheck(array $requestData): array
    {
        // Todo leave range check
        if (date('Y', strtotime($requestData['from'])) !== date('Y', strtotime($requestData['to']))) {
            return [
                'error' => true,
                'message' => 'Leave application date range should be in same year.'
            ];
        }

        // Todo Leave allocation check
        $leaveAllocation = $this->attendanceRepository->findOne(['year' => date('y', strtotime($requestData['from'])), 'employeeId' => $requestData['employeeId'], 'leaveTypeId' => $requestData['leaveTypeId'], 'status' => GlobalConstant::ACTIVE_STATUS], LeaveAllocation::class);
        if (!$leaveAllocation) {
            return [
                'error' => true,
                'message' => 'Leave Allocation setting is required.'
            ];
        }

        // Todo Leave type availability check
        $approvalHistory = $this->attendanceRepository->findLeaveApprovalHistory($requestData['employeeId'], $requestData['leaveTypeId'], $requestData['from'], $requestData['to']);
        $pendingDays = array_sum(array_column($approvalHistory, 'numberOfDays'));
        if (((float)$leaveAllocation->availedDays + (float)$pendingDays + (float)$requestData['numberOfDays']) > $leaveAllocation->remainingDays) {
            return [
                'error' => true,
                'message' => 'Due to some pending application your application exceed the remaining days.'
            ];
        }

        // Todo Leave Approval Policy check
        $approvalPolicies = $this->attendanceRepository->findAll(['employeeId' => $requestData['employeeId']], LeaveApprovalPolicy::class);
        if (empty($approvalPolicies)) {
            return [
                'error' => true,
                'message' => 'Leave approval policy setting is required.'
            ];
        }

        return [
            'error' => false,
            'message' => 'Application validated.',
            'data' => ['approvalPolicies' => $approvalPolicies, 'leaveAllocation' => $leaveAllocation]
        ];
    }

}