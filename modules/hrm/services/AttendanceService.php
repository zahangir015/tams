<?php

namespace app\modules\hrm\services;

use app\components\GlobalConstant;
use app\modules\hrm\models\Attendance;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\LeaveApplication;
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

    public function storeAttendance(Attendance $model): Attendance
    {

    }

    public function storeLeave(LeaveApplication $model, array $requestData): array
    {
        $dbTransaction = \Yii::$app->db->beginTransaction();
        try {


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

    public function applicationValidityCheck(Employee $employee, array $requestData): array
    {
        // Todo Leave allocation check
        // Todo Leave Approval Policy check
        // Todo Leave type availability check
    }

}