<?php

use app\components\Utilities;
use app\modules\account\models\Bill;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\account\models\search\BillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Bills');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Bill $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Utilities::getBasicActionColumnArray()
            ],
            [
                'attribute' => 'supplier',
                'value' => function ($model) {
                    return $model->supplier ? $model->supplier->company : null;
                },
                'label' => 'Supplier',
            ],
            'billNumber',
            'date',
            'paidAmount',
            'dueAmount',
            'discountedAmount',
            'refundAdjustmentAmount',
            'remarks:ntext',
            'status',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',

        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/account/bill/create'], [
                        'title' => Yii::t('app', 'Add Bill'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/account/bill/index'], [
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
