<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\Attendance;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\AttendanceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Attendances');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-index">
    <?php
    if (!Yii::$app->user->identity->employee) {
        ?>
        <div class="card">
            <div class="card-header bg-gray-dark">
                <h5>Attendance Entry</h5>
            </div>
            <div class="card-body">
                <?= Html::a(Yii::t('app', 'Entry'), ['entry'], [
                    'class' => 'btn btn-lg btn-warning',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a(Yii::t('app', 'Exit'), ['exit'], [
                    'class' => 'btn btn-lg btn-success',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <?php
    }
    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'employeeId',
                'value' => function ($model) {
                    return $model->employee->firstName . ' ' . $model->employee->lastName;
                },
                'filter' => Select2::widget(Utilities::ajaxDropDown('LeaveApprovalPolicySearch[employeeId]', '/hrm/employee/get-employees', false, 'employeeId', 'employeeId'))
            ],
            'date',
            'entry',
            'exit',
            'totalWorkingHours',
            [
                'attribute' => 'leaveTypeId',
                'value' => function ($model) {
                    return ($model->leaveType) ? $model->leaveType->name : null;
                },
                'filter' => Select2::widget(Utilities::ajaxDropDown('LeaveApprovalPolicySearch[employeeId]', '/hrm/employee/get-employees', false, 'employeeId', 'employeeId'))
            ],
            [
                'attribute' => 'isAbsent',
                'value' => function ($model) {
                    $labelClass = Utilities::statusLabelClass(!$model->isAbsent);
                    $labelText = GlobalConstant::YES_NO[$model->isLate];
                    return '<span class="right badge ' . $labelClass . '">' . $labelText . '</span>';
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'isLate',
                'value' => function ($model) {
                    $labelClass = Utilities::statusLabelClass(!$model->isLate);
                    $labelText = GlobalConstant::YES_NO[$model->isLate];
                    return '<span class="right badge ' . $labelClass . '">' . $labelText . '</span>';
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'isEarlyOut',
                'value' => function ($model) {
                    $labelClass = Utilities::statusLabelClass(!$model->isEarlyOut);
                    $labelText = GlobalConstant::YES_NO[$model->isEarlyOut];
                    return '<span class="right badge ' . $labelClass . '">' . $labelText . '</span>';
                },
                'format' => 'html'
            ],
            'totalLateInTime',
            'totalEarlyOutTime',
            'remarks',
            'employeeNote',
            //'status',
            //'createdBy',
            //'updatedBy',
            //'createdAt',
            //'updatedAt',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Attendance $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Utilities::getBasicActionColumnArray()
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/hrm/attendance/create'], [
                        'title' => Yii::t('app', 'Add Attendance'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/attendance/index'], [
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Reset Grid')
                    ]),
            ],
            '{export}',
            '{toggleData}'
        ],
        //'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<i class="fas fa-list-alt"></i> ' . Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>
</div>
