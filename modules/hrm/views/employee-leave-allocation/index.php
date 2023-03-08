<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\hrm\models\LeaveAllocation;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\LeaveAllocationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Employee Leave Allocations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-leave-allocation-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'employeeId',
                'value' => function ($model) {
                    return $model->employee->firstName . '' . $model->employee->lastName;
                },
                'filter' => Select2::widget(WidgetHelper::ajaxSelect2Widget('employeeId', '/hrm/employee/get-employees', false, 'employeeId', 'employeeId'))
            ],
            'year',
            [
                'attribute' => 'leaveTypeId',
                'value' => function ($model) {
                    return $model->leaveType->name;
                },
            ],
            'totalDays',
            'availedDays',
            'remainingDays',
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
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, LeaveAllocation $model, $key, $index, $column) {
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
                    Html::a('<i class="fas fa-plus"></i>', ['/hrm/employee-leave-allocation/create'], [
                        'title' => Yii::t('app', 'Add Employee Allocation'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/employee-leave-allocation/index'], [
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
