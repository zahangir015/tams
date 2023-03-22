<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\hrm\models\EmployeePayroll;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\EmployeePayrollSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Employee Payrolls');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-payroll-index">
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
            'gross',
            'tax',
            'paymentMode',
            'remarks',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, EmployeePayroll $model, $key, $index, $column) {
<<<<<<< HEAD
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
=======
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
>>>>>>> b152a25296158dedb61227bd6ce4fa2c46564226
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Utilities::getBasicActionColumnArray()
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/hrm/employee-payroll/create'], [
                        'title' => Yii::t('app', 'Add Employee Payroll'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
<<<<<<< HEAD
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/employee-payroll/index'], [
=======
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/employee-leave-allocation/index'], [
>>>>>>> b152a25296158dedb61227bd6ce4fa2c46564226
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Reset Grid')
                    ]),
            ],
            '{export}',
            '{toggleData}'
        ],
<<<<<<< HEAD
        'pjax' => true,
=======
        //'pjax' => true,
>>>>>>> b152a25296158dedb61227bd6ce4fa2c46564226
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
