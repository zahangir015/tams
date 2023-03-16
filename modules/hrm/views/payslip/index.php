<?php

use app\modules\hrm\models\Payslip;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\PayslipSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Payslips');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payslip-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Payslip'), ['create'], ['class' => 'btn btn-success']) ?>
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
            'month',
            'year',
            //'gross',
            //'tax',
            //'lateFine',
            //'totalAdjustment',
            //'totalDeduction',
            //'totalPaid',
            //'paymentMode',
            //'processStatus',
            //'remarks',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Payslip $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
