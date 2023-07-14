<?php

namespace app\modules\hrm\services;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\components\HrmConstant;
use app\modules\hrm\models\Attendance;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\EmployeeShift;
use app\modules\hrm\models\LeaveAllocation;
use app\modules\hrm\models\LeaveApplication;
use app\modules\hrm\models\LeaveApprovalHistory;
use app\modules\hrm\models\LeaveApprovalPolicy;
use app\modules\hrm\models\Roster;
use app\modules\hrm\models\Shift;
use app\modules\hrm\repositories\AttendanceRepository;
use app\modules\sale\models\Customer;
use DateInterval;
use DatePeriod;
use DateTime;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
        $dbTransaction = Yii::$app->db->beginTransaction();
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
                $leaveApprovalHistory->load(['LeaveApprovalHistory' => $approvalPolicy->getAttributes(['employeeId', 'requestedTo', 'approvalLevel'])]);
                $leaveApprovalHistory->leaveApplicationId = $leaveApplication->id;
                $leaveApprovalHistory = $this->attendanceRepository->store($leaveApprovalHistory);

                if ($leaveApprovalHistory->hasErrors()) {
                    throw new Exception('Leave Approval History creation failed - ' . Utilities::processErrorMessages($leaveApprovalHistory->getErrors()));
                }
            }

            // Attendance update or entry
            $attendanceProcessResponse = self::processAttendanceForLeaveApplication($leaveApplication);
            if ($attendanceProcessResponse['error']) {
                throw new Exception($attendanceProcessResponse['message']);
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

    public function updateLeave(LeaveApplication $leaveApplication, array $requestData, array $relatedData): array
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            // Application loading
            if (!$leaveApplication->load($requestData)) {
                throw new Exception('Leave application loading failed - ' . Utilities::processErrorMessages($leaveApplication->getErrors()));
            }

            // Update application
            $leaveApplication = $this->attendanceRepository->store($leaveApplication);
            if ($leaveApplication->hasErrors()) {
                throw new Exception('Leave application updating failed - ' . Utilities::processErrorMessages($leaveApplication->getErrors()));
            }

            $dbTransaction->commit();
            return [
                'error' => false,
                'message' => 'Leave application updated successfully.'
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
        // Leave range check
        if (date('Y', strtotime($requestData['from'])) !== date('Y', strtotime($requestData['to']))) {
            return [
                'error' => true,
                'message' => 'Leave application date range should be in same year.'
            ];
        }

        // Leave allocation check
        $leaveAllocation = $this->attendanceRepository->findOne(
            [
                'year' => date('Y', strtotime($requestData['from'])),
                'employeeId' => $requestData['employeeId'],
                'leaveTypeId' => $requestData['leaveTypeId'],
                'status' => GlobalConstant::ACTIVE_STATUS
            ], LeaveAllocation::class);
        if (!$leaveAllocation) {
            return [
                'error' => true,
                'message' => 'Leave Allocation setting is required.'
            ];
        }

        // Todo Leave type availability check
        $approvalHistory = $this->attendanceRepository->findLeaveApprovalHistory($requestData['employeeId'], $requestData['leaveTypeId'], $requestData['from'], $requestData['to']);
        if (!empty($approvalHistory)) {
            $pendingDays = array_sum(array_column($approvalHistory, 'numberOfDays'));
            if (((float)$leaveAllocation->availedDays + (float)$pendingDays + (float)$requestData['numberOfDays']) > $leaveAllocation->remainingDays) {
                return [
                    'error' => true,
                    'message' => 'Due to some pending application your application exceed the remaining days.'
                ];
            }
        }

        // Leave Approval Policy check
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

    private function processAttendanceForLeaveApplication($leaveApplication): array
    {
        $attendances = $this->attendanceRepository->findAttendances($leaveApplication->employeeId, $leaveApplication->from, $leaveApplication->to);
        if (!empty($attendances)) {
            foreach ($attendances as $attendance) {
                $attendance->leaveTypeId = $leaveApplication->leaveTypeId;
                $attendance->leaveApplicationId = $leaveApplication->id;
                $attendance->remarks = HrmConstant::NUMBER_OF_DAYS[$leaveApplication->numberOfDays] . ' ' . $leaveApplication->leaveType->name . ' Leave';
                $attendance = $this->attendanceRepository->store($attendance);
                if ($attendance->hasErrors()) {
                    return [
                        'error' => true,
                        'message' => 'Attendance update for leave application failed - ' . Utilities::processErrorMessages($attendance->getErrors())
                    ];
                }
            }

            return [
                'error' => false,
                'message' => 'Attendance processed successfully.'
            ];
        } else {

            // Shift Check
            $shift = $this->attendanceRepository->findOne(['employeeId' => $leaveApplication->employeeId, 'status' => GlobalConstant::ACTIVE_STATUS], EmployeeShift::class);
            if (!$shift) {
                return [
                    'error' => true,
                    'message' => 'Employee shift setup in required.'
                ];
            }
            $date1 = DateTime::createFromFormat('Y-m-d', $leaveApplication->from);
            $date2 = DateTime::createFromFormat('Y-m-d', $leaveApplication->to);
            $diff = $date1->diff($date2)->m;
            if ($diff >= 1) {
                $begin = new DateTime($leaveApplication->from);
                $end = new DateTime($leaveApplication->to);
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);

                foreach ($period as $dt) {
                    $date = $dt->format('Y-m-d');
                    $roster = $this->attendanceRepository->findOne(['employeeId' => $leaveApplication->employeeId, 'rosterDate' => $date, 'status' => GlobalConstant::ACTIVE_STATUS], Roster::class);

                    $attendance = new Attendance();
                    $attendance->employeeId = $leaveApplication->employeeId;
                    $attendance->date = $date;
                    $attendance->leaveType = $leaveApplication->leaveType;
                    $attendance->leaveApplicationId = $leaveApplication->id;
                    $attendance->shiftId = ($roster) ? $roster->shiftId : $shift->id;
                    $attendance->remarks = HrmConstant::NUMBER_OF_DAYS[$leaveApplication->numberOfDays] . ' ' . $leaveApplication->leaveType->name . ' Leave';
                    $attendance = $this->attendanceRepository->store($attendance);
                    if ($attendance->hasErrors()) {
                        return [
                            'error' => true,
                            'message' => 'Attendance entry for leave application failed - ' . Utilities::processErrorMessages($attendance->getErrors())
                        ];
                    }
                }
            } else {
                $date = $leaveApplication->from;
                $roster = $this->attendanceRepository->findOne(['employeeId' => $leaveApplication->employeeId, 'rosterDate' => $date, 'status' => GlobalConstant::ACTIVE_STATUS], Roster::class);

                $attendance = new Attendance();
                $attendance->employeeId = $leaveApplication->employeeId;
                $attendance->date = $date;
                $attendance->leaveTypeId = $leaveApplication->leaveTypeId;
                $attendance->leaveApplicationId = $leaveApplication->id;
                $attendance->shiftId = ($roster) ? $roster->shiftId : $shift->id;
                $attendance->remarks = HrmConstant::NUMBER_OF_DAYS[$leaveApplication->numberOfDays] . ' Leave';
                $attendance = $this->attendanceRepository->store($attendance);
                if ($attendance->hasErrors()) {
                    return [
                        'error' => true,
                        'message' => 'Attendance entry for leave application failed - ' . Utilities::processErrorMessages($attendance->getErrors())
                    ];
                }
            }

            return [
                'error' => false,
                'message' => 'Attendance processed successfully.'
            ];

        }
    }

    public function approve(array $queryArray, string $class, array $withArray = []): ActiveRecord
    {
        $model = self::findModel($queryArray, $class, $withArray);

        if ($model->requested->userId !== Yii::$app->user->id) {
            Yii::$app->session->setFlash('danger', 'Invalid request.');
        }

        $model->approvalStatus = HrmConstant::APPROVAL_STATUS['Approved'];
        return $this->attendanceRepository->store($model);
    }

    public function storeAttendance(Attendance $model, array $requestData): array
    {
        $employeeShift = $this->attendanceRepository->findOne(['shiftId' => $model->shiftId, 'employeeId' => $model->employeeId], EmployeeShift::class, ['employee']);
        if (!$employeeShift) {
            return [
                'error' => true,
                'message' => 'Employee shift not found.'
            ];
        }

        $employeeInTime = new DateTime($requestData['date'] . $requestData['entry']);
        $employeeOutTime = new DateTime($requestData['date'] . $requestData['exit']);
        $totalWorkingHours = $employeeOutTime->diff($employeeInTime);

        $late = self::calculateLate($employeeInTime, $employeeShift);
        $model->lateInTime = $late['late_in_time'];
        $model->lateIn = $late['late_in'];

        $workingTimeForShift = self::calculateWorkingTime($totalWorkingHours, $employeeShift);
        $model->overTime = $workingTimeForShift['working']['overtime'];
        $model->earlyOutTime = $workingTimeForShift['working']['early_out_time'];
        $model->earlyOut = $workingTimeForShift['working']['early_out'];

        $model->employeeId = $requestData['employeeId'];
        $model->date = $requestData['date'];
        $model->shiftId = $employeeShift->shift->id;
        $model->createdBy = Yii::$app->user->id;
        $model->createdAt = time();
        $model->totalWorkingHours = str_pad($totalWorkingTime->h, 2, '0', STR_PAD_LEFT) . ':' .
            str_pad($totalWorkingTime->i, 2, '0', STR_PAD_LEFT) . ':00';

        return $model->save();
    }

    public function attendanceEntry(Attendance $model): array
    {
        $employeeShift = null;
        $employee = Employee::findOne(['id' => 2]);
        $roster = $this->attendanceRepository->findOne(['employeeId' => $employee->id, 'rosterDate' => date('Y-m-d')], Roster::class, [['employee', 'shift']]);
        if (!$roster) {
            $employeeShift = $this->attendanceRepository->findOne(['employeeId' => $employee->id], EmployeeShift::class, ['employee', 'shift']);
            if (!$employeeShift) {
                return [
                    'error' => true,
                    'message' => 'Employee shift not found. Please Setup employee shift.'
                ];
            }
        }

        $model->entry = date('H:i:00');
        $model->employeeId = $employee->id;
        $employeeInTime = new DateTime(date('Y-m-d').' '.$model->entry);
        $lateCalculationResponse = self::calculateLate($employeeInTime, ($roster) ? $roster->shift : $employeeShift->shift);
        $model->load(['Attendance' => $lateCalculationResponse]);
        $model->date = date('Y-m-d');
        $model->shiftId = ($roster) ? $roster->shift->id : $employeeShift->shift->id;
        $model->rosterId = ($roster) ? $roster->id : null;
        $model = $this->attendanceRepository->store($model);
        if ($model->hasErrors()) {
            return [
                'error' => true,
                'message' => 'Attendance entry failed - '.Utilities::processErrorMessages($model->getErrors())
            ];
        }

        return [
            'error' => false,
            'message' => 'Attendance entry successfully done.'
        ];
    }

    public function attendanceExit(Attendance $model)
    {
        $attendanceData = \app\modules\leaveAttendance\models\Attendance::findOne(['date' => date('Y-m-d'), 'employeeId' => Yii::$app->user->identity->employeeDetails->id]);
        $employeeShift = \app\modules\leaveAttendance\components\Attendance::getShiftByEmployee(Yii::$app->user->identity->employeeDetails, date('Y-m-d'));
        if ($attendanceData && !empty($attendanceData->entry)) {
            $employeeOutTime = new DateTime(date('Y-m-d H:i:s'));
            $existingEntry = date('Y-m-d H:i:s', strtotime($attendanceData->date . ' ' . $attendanceData->entry));
            $employeeInTime = new DateTime($existingEntry);
            $totalWorkingTime = $employeeOutTime->diff($employeeInTime);
            $workingTimeForShift = \app\modules\leaveAttendance\components\Attendance::calculateWorkingTime($totalWorkingTime, $employeeShift);
            $rows = [
                'exit' => date('H:i:00'),
                'overTime' => $workingTimeForShift['working']['overtime'],
                'earlyOut' => $workingTimeForShift['working']['early_out'],
                'earlyOutTime' => $workingTimeForShift['working']['early_out_time'],
                'totalWorkingHours' => str_pad($totalWorkingTime->h, 2, '0', STR_PAD_LEFT) . ':' .
                    str_pad($totalWorkingTime->i, 2, '0', STR_PAD_LEFT) . ':00',
            ];

            \app\modules\leaveAttendance\models\Attendance::updateAll($rows, ['date' => date('Y-m-d'), 'employeeId' => Yii::$app->user->identity->employeeDetails->id]);
        }

        Yii::$app->session->setFlash('success', 'Attendance exit Updated.');
        return $this->redirect(['index']);
    }

    private static function calculateLate($employeeInTime, Shift $shift): array
    {
        $shiftInTime = new DateTime(date('Y-m-d') . $shift->entryTime);
        $shiftInTimeToCalculateLate = clone $shiftInTime;
        $shiftInTimeToCalculateLate->add(new DateInterval('PT' . HrmConstant::FLEXIBLE_ENTRY_TIME_IN_MINUTE . 'M'));

        if ($employeeInTime <= $shiftInTimeToCalculateLate) {
            return [
                'totalLateInTime' => null,
                'isLate' => 0
            ];
        }

        $inTimeDiff = $employeeInTime->diff($shiftInTime);
        return [
            'totalLateInTime' => str_pad($inTimeDiff->h, 2, '0', STR_PAD_LEFT) . ':' .
                str_pad($inTimeDiff->i, 2, '0', STR_PAD_LEFT) . ':00',
            'isLate' => 1
        ];
    }

    public static function dashboardReport(): array
    {
        $employee = Employee::find()
            ->where(['userId' => Yii::$app->user->id])
            ->andWhere(['agencyId' => Yii::$app->user->identity->agencyId])
            ->one();
        //TODO Attendance Details
        $attendanceData = Attendance::find()
            ->select(
                [
                    new Expression('SUM(isAbsent) as totalAbsent'),
                    new Expression('SUM(isLate) as totalLate'),
                    new Expression('SUM(isEarlyOut) as totalEarlyOut'),
                ]
            )
            ->where([Attendance::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([Attendance::tableName().'.employeeId' => $employee->id])
            ->andWhere(['between', 'date', date('Y-m-01'), date('Y-m-t')])
            ->asArray()
            ->one();
        //TODO Leave Details
        $leaveAllocationData = LeaveAllocation::find()
            ->select(
                [
                    'employeeId',
                    'leaveTypeId',
                    'year',
                    'totalDays',
                    'availedDays',
                    'remainingDays'
                ]
            )
            ->where([LeaveAllocation::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([LeaveAllocation::tableName().'.employeeId' => $employee->id])
            ->asArray()->all();
        
        return [
            $attendanceData,
            $leaveAllocationData
        ];
    }
}