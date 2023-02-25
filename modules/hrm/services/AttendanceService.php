<?php

namespace app\modules\hrm\services;

use app\components\GlobalConstant;
use app\modules\hrm\models\Attendance;
use app\modules\hrm\repositories\AttendanceRepository;
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

}