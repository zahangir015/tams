<?php

use app\components\Utilities;
use app\modules\hrm\models\Payslip;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\PayslipSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Payslips');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payslip-index">
    <p>
        <?= Html::a(Yii::t('app', 'Generate Payslip'), ['generate'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Send Payslip'), ['send-mail'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'employeeId',
            'month',
            'year',
            'gross',
            'tax',
            'lateFine',
            'totalAdjustment',
            'totalDeduction',
            'totalPaid',
            'paymentMode',
            'processStatus',
            'remarks',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Payslip $model, $key, $index, $column) {
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
                    Html::a('<i class="fas fa-plus"></i>', ['/hrm/payslip/create'], [
                        'title' => Yii::t('app', 'Create Payslip'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/payslip/index'], [
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Reset Grid')
                    ]),
            ],
            '{export}',
            '{toggleData}'
        ],
        'pjax' => true,
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
