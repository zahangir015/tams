<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\account\models\search\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'kartik\grid\ActionColumn',
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
                    /*'preview' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-envelope-open"></i>', ['preview', 'uid' => $model->uid], [
                            'title' => Yii::t('app', 'Preview of Invoice'),
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                    'send' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-paper-plane"></i>', ['send', 'uid' => $model->uid], [
                            'title' => Yii::t('app', 'Send Invoice'),
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },*/
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
                            'title' => Yii::t('app', 'Download Invoice'),
                            'class' => 'btn btn-warning btn-xs'
                        ]);
                    },
                ]
            ],
            [
                'attribute' => 'customer',
                'label' => 'Customer Code',
                'value' => 'customer.customerCode'
            ],
            [
                'attribute' => 'customer',
                'value' => 'customer.company'
            ],
            [
                'attribute' => 'category',
                'value' => 'customer.customerCategory'
            ],
            [
                'attribute' => 'invoiceNumber',
                'label' => 'invoice#'
            ],
            [
                'attribute' => 'paidAmount',
                'label' => 'Paid',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM
            ],
            [
                'attribute' => 'dueAmount',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM
            ],
            [
                'attribute' => 'adjustmentAmount',
                'label' => 'Adjustment',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM
            ],
            [
                'attribute' => 'expectedDate',
                'label' => 'Expected Date',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'expectedDate',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
            [
                'attribute' => 'date',
                'label' => 'Date',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
            'discount',
            'comment:ntext',
            'createdAt:date',
            'createdBy',
            'updatedBy',
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/account/invoice/create'], [
                        'title' => Yii::t('app', 'Add Category'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/account/invoice/index'], [
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
        'responsiveWrap' => false,
        'hover' => true,
        'panel' => [
            'heading' => '<i class="fas fa-list-alt"></i> ' . Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]) ?>
</div>
