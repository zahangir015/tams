<?php

use app\modules\hrm\models\EmployeePayroll;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\EmployeePayrollSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Employee Payrolls');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-payroll-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Employee Payroll'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'employeeId',
            'gross',
            'tax',
            //'paymentMode',
            //'remarks',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, EmployeePayroll $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
