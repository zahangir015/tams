<?php

namespace app\controllers;

use app\components\GlobalConstant;
use app\modules\hrm\models\PublicHoliday;
use app\modules\hrm\models\Roster;
use edofre\fullcalendar\models\Event;
use app\modules\hrm\models\Weekend;
use yii\helpers\ArrayHelper;

class CalendarController extends ParentController
{
    /**
     * Show calendar
     * @return string
     */
    public function actionIndex(): string
    {
        $events = [];
        $employee = \Yii::$app->user->identity->employeeDetails;
        if (!isset($employee)) {
            return $this->render('index', ['events' => $events]);
        }

        $holidays = PublicHoliday::findAll(['status' => GlobalConstant::ACTIVE_STATUS]);

        foreach ($holidays as $holiday) {
            $events[] = new Event(
                [
                    'id' => $holiday->id,
                    'title' => $holiday->title,
                    'className' => "fc-event-primary",
                    'start' => $holiday->date,
                    'end' => $holiday->date
                ]
            );
        }

        $startDate = date('Y-01-01');
        $endDate = date('Y-12-t');

        $weekendEvent = [];
        $rosterEvent = [];

        $weekends = ArrayHelper::getColumn(Weekend::find()->where(['departmentId' => $employee->employeeDesignation->departmentId])->all(), 'dayName');
        for ($i = strtotime($startDate); $i <= strtotime($endDate); $i = strtotime('+1 day', $i)) {
            if (in_array(date('l', $i), $weekends)) {
                $weekendEvent[] = new Event([
                    'id' => $i,
                    'title' => 'Weekend',
                    'className' => "fc-event-danger",
                    'start' => date('Y-m-d', $i),
                    'end' => date('Y-m-d', $i)
                ]);
            }

            $roster = RosterSchedule::find()->where(['employeeId' => $employee->id, 'date' => date('Y-m-d', $i)])->one();
            if ($roster) {
                $rosterEvent[] = new Event([
                    'id' => $i,
                    'title' => 'Roster',
                    'className' => "fc-event-info",
                    'start' => date('Y-m-d', $i),
                    'end' => date('Y-m-d', $i)
                ]);
            }
        }

        return $this->render('index', ['events' => array_merge(array_merge($weekendEvent, $events), $rosterEvent)]);
    }
}
