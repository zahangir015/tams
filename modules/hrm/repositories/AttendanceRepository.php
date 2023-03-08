<?php

namespace app\modules\hrm\repositories;

use app\components\GlobalConstant;
use app\modules\hrm\components\HrmConstant;
use app\modules\hrm\models\Attendance;
use app\modules\hrm\models\LeaveApplication;
use app\modules\hrm\models\LeaveApprovalHistory;
use app\repository\ParentRepository;

class AttendanceRepository extends ParentRepository
{

    public function storeAttendance(Attendance $model)
    {
    }

    public function findLeaveApprovalHistory($employeeId, $leaveTypeId, $from, $to): array
    {
        return LeaveApplication::find()
            ->joinWith(['leaveApprovalHistories' => function ($query) {
                $query->where(['<>', 'approvalStatus', HrmConstant::APPROVAL_STATUS['Approved']])
                    ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS]);
            }])
            ->where(['employeeId' => $employeeId])
            ->andWhere(['leaveTypeId' => $leaveTypeId])
            ->andWhere(['YEAR(from)' => date('y', strtotime($from))])
            ->asArray()->all();
    }
}