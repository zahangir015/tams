<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\EmployeeShift;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\EmployeeShiftSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Employee Shifts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-shift-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'departmentId',
                'value' => function ($model) {
                    return $model->department ? $model->department->name : null;
                },
                'filter' => Select2::widget(Utilities::ajaxDropDown('EmployeeShiftSearch[departmentId]', '/hrm/department/get-departments', false, 'departmentId', 'departmentId'))
            ],
            [
                'attribute' => 'shiftId',
                'value' => function ($model) {
                    return $model->shift->title;
                },
                'filter' => Select2::widget(Utilities::ajaxDropDown('EmployeeShiftSearch[shiftId]', '/hrm/shift/get-shifts', false, 'shiftId', 'shiftId'))
            ],
            [
                'attribute' => 'employeeId',
                'value' => function ($model) {
                    return $model->employee->firstName.' '.$model->employee->lastName;
                },
                'filter' => Select2::widget(Utilities::ajaxDropDown('EmployeeShiftSearch[employeeId]', '/hrm/employee/get-employees', false, 'employeeId', 'employeeId'))
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($model) {
                    $labelClass = Utilities::statusLabelClass($model->status);
                    $labelText = ($model->status) ? 'Active' : 'Inactive';
                    return '<span class="right badge ' . $labelClass . '">' . $labelText . '</span>';
                },
                'filter' => GlobalConstant::DEFAULT_STATUS,
                'format' => 'html',
            ],
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, EmployeeShift $model, $key, $index, $column) {
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
                    Html::a('<i class="fas fa-plus"></i>', ['/hrm/employee-shift/create'], [
                        'title' => Yii::t('app', 'Add Department Shift'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/employee-shift/index'], [
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
