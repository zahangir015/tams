<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

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
                'attribute' => 'QuoteAmount',
                'label' => 'Quoted',
                'value' => function ($model) {
                    return ($model->paidAmount + $model->dueAmount);
                },
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM
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
            'expectedDate',
            'date',
            'discount',
            'comment:ntext',
            'createdAt:date',
            'createdBy',
            'updatedBy',
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => true,
                'vAlign' => 'middle',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return \yii\helpers\Url::to([$action, 'uid' => $model->uid]);
                },

                'template' => '{view} {preview} {send} {payment}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-eye"></i>', ['view', 'uid' => $model->uid], [
                            'title' => 'view', 'data-pjax' => '0',
                        ]);
                    },
                    'preview' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-envelope-open"></i>', ['preview', 'uid' => $model->uid], [
                            'title' => Yii::t('app', 'Preview of Invoice'),
                        ]);
                    },
                    'send' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-paper-plane"></i>', ['send', 'uid' => $model->uid], [
                            'title' => Yii::t('app', 'Send Invoice'),
                        ]);
                    },
                    'payment' => function ($url, $model, $key) {
                        if ($model->dueAmount == 0) {
                            return Html::a('<i class="fa fa-credit-card"></i>', ['pay', 'uid' => $model->uid],
                                [
                                    'title' => Yii::t('app', 'pay'),
                                    'class' => 'btn btn-light-primary btn-icon btn-sm m-2',
                                    'data-pjax' => '0',
                                ]);
                        } else {
                            return false;
                        }
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(Utils::svgDeleteIcon(), ['delete', 'uid' => $model->uid], [
                            'title' => 'delete',
                            'class' => 'btn btn-light btn-hover-primary btn-icon btn-sm m-2',
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                ]
            ]
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
