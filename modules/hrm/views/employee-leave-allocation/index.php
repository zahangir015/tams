<?php

use app\modules\hrm\models\EmployeeLeaveAllocation;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\EmployeeLeaveAllocationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Employee Leave Allocations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-leave-allocation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Employee Leave Allocation'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'employeeId',
                'value' => function ($model) {
                    return $model->employee->firstName . '' . $model->employee->lastName;
                },
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
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, EmployeeLeaveAllocation $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
