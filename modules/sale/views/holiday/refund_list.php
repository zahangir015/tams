<?php

use app\components\GlobalConstant;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\holiday\Holiday;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\holiday\HolidaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Refund Holidays');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'motherId',
            [
                'attribute' => 'invoice',
                'value' => function ($model) {
                    return $model->invoice->invoiceNumber;
                },
                'label' => 'Invoice',
            ],
            [
                'attribute' => 'holidayCategoryId',
                'value' => function ($model) {
                    return $model->holidayCategory->name;
                },
                'label' => 'Category',
                'filter' => $holidayCategories
            ],
            [
                'attribute' => 'identificationNumber',
                'value' => 'identificationNumber',
                'label' => 'Identification#',
            ],
            [
                'attribute' => 'customer',
                'value' => function ($model) {
                    return $model->customer->company . '(' . $model->customer->customerCode . ')';
                },
                'label' => 'Customer',
            ],
            [
                'attribute' => 'customerCategory',
                'value' => 'customerCategory',
                'label' => 'Category',
                'filter' => GlobalConstant::CUSTOMER_CATEGORY
            ],
            [
                'attribute' => 'type',
                'value' => 'type',
                'filter' => GlobalConstant::TICKET_TYPE_FOR_CREATE
            ],
            [
                'attribute' => 'issueDate',
                'label' => 'ISSUE',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'issueDate',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
            [
                'attribute' => 'departureDate',
                'label' => 'Departure',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'departureDate',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
            [
                'attribute' => 'refundRequestDate',
                'label' => 'Refund Request',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'refundRequestDate',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
            'quoteAmount',
            'costOfSale',
            'netProfit',
            'receivedAmount',
            'paymentStatus',
            [
                'attribute' => 'isOnlineBooked',
                'value' => function($model){
                    return GlobalConstant::BOOKING_TYPE[$model->isOnlineBooked];
                },
                'filter' => GlobalConstant::BOOKING_TYPE
            ],
            'route',
            [
                'attribute' => 'isRefunded',
                'value' => function ($model) {
                    return  GlobalConstant::YES_NO[$model->holidayRefund->isRefunded];
                },
                'filter' => GlobalConstant::YES_NO
            ],
            /*[
                'attribute' => 'refundFromSupplierStatus',
                'label' => 'Refund From Supplier Status',
                'value' => function ($model) {
                    return $model->holidayRefund->refundFromSupplierStatus;
                }
            ],*/
            [
                'attribute' => 'refundStatus',
                'label' => 'Refund Status',
                'value' => function ($model) {
                    return $model->holidayRefund->refundStatus;
                },
                'filter' => ServiceConstant::REFUND_STATUS
            ],
            /*[
                'attribute' => 'refundType',
                'label' => 'Refund Type',
                'value' => function ($model) {
                    return $model->holidayRefund->refundType;
                },
                'filter' => ServiceConstant::REFUND_TYPE
            ],*/
            [
                'attribute' => 'refundMedium',
                'label' => 'Medium',
                'value' => function ($model) {
                    return $model->holidayRefund->refundMedium;
                },
                'filter' => ServiceConstant::REFUND_MEDIUM
            ],
            [
                'attribute' => 'refundMethod',
                'label' => 'Method',
                'value' => function ($model) {
                    return $model->holidayRefund->refundMethod;
                },
                'filter' => ServiceConstant::REFUND_METHOD
            ],
            [
                'attribute' => 'refundDate',
                'label' => 'Refund Date',
                'value' => function ($model) {
                    return $model->holidayRefund->refundDate;
                }
            ],
            [
                'attribute' => 'refundedAmount',
                'label' => 'Refunded Amount',
                'value' => function ($model) {
                    return $model->holidayRefund->refundedAmount;
                }
            ],
            [
                'attribute' => 'serviceCharge',
                'label' => 'Service Charge',
                'value' => function ($model) {
                    return $model->holidayRefund->serviceCharge;
                }
            ],
            [
                'attribute' => 'supplierRefundCharge',
                'label' => 'Supplier Charge',
                'value' => function ($model) {
                    return $model->holidayRefund->supplierRefundCharge;
                }
            ],
            //'baggage',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => 'kartik\grid\ActionColumn',
                'urlCreator' => function ($action, $model) {
                    return Url::to([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return \yii\bootstrap4\Html::a('<i class="fa fa-info-circle"></i>', ['view', 'uid' => $model->uid], [
                            'title' => Yii::t('app', 'View'),
                            'data-pjax' => '0',
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                    'edit' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-pencil-alt"></i>', ['refund-update', 'uid' => $model->uid], [
                            'title' => Yii::t('app', 'Update'),
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-trash-alt"></i>', ['delete', 'uid' => $model->uid], [
                            'title' => Yii::t('app', 'Delete'),
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                ]
            ]
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/holiday/create'], [
                        'title' => Yii::t('app', 'Add Holiday'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/holiday/refund-list'], [
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
    ]); ?>
</div>
