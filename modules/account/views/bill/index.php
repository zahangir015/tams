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
                'class' => ActionColumn::class,
                'vAlign' => 'middle',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {payment} {download} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-info-circle"></i>', ['view', 'uid' => $model->uid], [
                            'title' => 'view',
                            'data-pjax' => '0',
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                    'payment' => function ($url, $model, $key) {
                        if ($model->dueAmount != 0) {
                            return Html::a('<i class="fa fa-credit-card"></i>', ['pay', 'uid' => $model->uid],
                                [
                                    'title' => Yii::t('app', 'pay'),
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-primary btn-xs'
                                ]);
                        } else {
                            return false;
                        }
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-trash-alt"></i>', ['delete', 'uid' => $model->uid], [
                            'title' => 'delete',
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                            'class' => 'btn btn-danger btn-xs'
                        ]);
                    },
                    'download' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-download"></i>', ['download', 'uid' => $model->uid], [
                            'target' => "_blank",
                            'title' => Yii::t('app', 'Download Invoice'),
                            'class' => 'btn btn-warning btn-xs',
                        ]);
                    },
                ],
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
