<?php

use app\components\Utilities;
use app\modules\hrm\components\HrmConstant;
use app\modules\hrm\models\PayrollType;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\PayrollTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Payroll Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-type-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'amountType',
                'value' => function ($model) {
                    return HrmConstant::AMOUNT_TYPE[$model->amountType];
                },
                'filter' => HrmConstant::AMOUNT_TYPE
            ],
            [
                'attribute' => 'calculatingMethod',
                'value' => function ($model) {
                    return HrmConstant::CALCULATING_METHOD[$model->calculatingMethod];
                },
                'filter' => HrmConstant::CALCULATING_METHOD
            ],
            [
                'attribute' => 'category',
                'value' => function ($model) {
                    return HrmConstant::PAYROLL_CATEGORY[$model->category];
                },
                'filter' => HrmConstant::PAYROLL_CATEGORY
            ],
            'amount',
            'order',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, PayrollType $model, $key, $index, $column) {
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
                    Html::a('<i class="fas fa-plus"></i>', ['/hrm/payroll-type/create'], [
                        'title' => Yii::t('app', 'Add Shift'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/payroll-type/index'], [
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
