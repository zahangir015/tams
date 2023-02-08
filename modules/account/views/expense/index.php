<?php

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\account\models\Expense;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ExpenseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Expenses');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="expense-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'identificationNumber',
            [
                'attribute' => 'category',
                'value' => function ($model) {
                    return $model->category->name;
                }
            ],
            [
                'attribute' => 'subCategory',
                'value' => function ($model) {
                    return $model->subCategory->name;
                }
            ],
            [
                'attribute' => 'supplier',
                'value' => function ($model) {
                    return isset($model->supplier) ? $model->supplier->name : null;
                }
            ],
            'accruingMonth',
            'timingOfExp',
            'totalCost',
            'totalPaid',
            [
                'attribute' => 'paymentStatus',
                'value' => function ($model) {
                    return $model->paymentStatus;
                },
                'filter' => GlobalConstant::PAYMENT_STATUS
            ],
            //'notes:ntext',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($model) {
                    $labelClass = Helper::statusLabelClass($model->status);
                    return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::DEFAULT_STATUS[$model->status] . '</span>';
                },
                'filter' => GlobalConstant::DEFAULT_STATUS,
                'format' => 'html',
            ],
            'createdAt',
            'createdBy',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Expense $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'width' => '200px',
                'template' => '{view} {edit} {pay} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Helper::getBasicActionColumnWithPayArray()
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/account/expense/create'], [
                        'title' => Yii::t('app', 'Add Airline'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/account/expense/index'], [
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
