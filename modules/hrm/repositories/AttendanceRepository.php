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
    public function findLeaveApprovalHistory($employeeId, $leaveTypeId, $from, $to): array
    {
        return LeaveApplication::find()
            ->joinWith(['leaveApprovalHistories' => function ($query) {
                return $query->where(['<>', 'approvalStatus', HrmConstant::APPROVAL_STATUS['Approved']])
                    ->andWhere([LeaveApprovalHistory::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS]);
            }])
            ->where(['employeeId' => $employeeId])
            ->andWhere(['leaveTypeId' => $leaveTypeId])
            ->andWhere([LeaveApplication::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            //->andWhere(['YEAR(from)' => date('Y', strtotime($from))])
            ->asArray()->all();
    }

    public function findAttendances($employeeId, $from, $to): array
    {
        return Attendance::find()
            ->where(['employeeId' => $employeeId])
            ->andWhere(['between', 'date', $from, $to])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}