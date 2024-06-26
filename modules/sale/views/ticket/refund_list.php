<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\ticket\Ticket;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\ticket\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Refund Tickets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'motherTicketId',
                'value' => 'motherTicketId',
                'label' => 'Mother'
            ],
            [
                'attribute' => 'airline',
                'value' => function ($model) {
                    return $model->airline->name . '(' . $model->airline->code . ')';
                },
                'label' => 'Airline',
            ],
            [
                'attribute' => 'provider',
                'value' => function ($model) {
                    return $model->provider ? $model->provider->name : null;
                },
                'label' => 'Provider',
            ],
            [
                'attribute' => 'invoice',
                'value' => function ($model) {
                    return $model->invoice->invoiceNumber;
                },
                'label' => 'Invoice',
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
            'paxName',
            [
                'attribute' => 'paxType',
                'value' => function ($model) {
                    return ServiceConstant::PAX_TYPE[$model->paxType];
                },
                'filter' => ServiceConstant::PAX_TYPE
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
            'eTicket',
            'pnrCode',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return ServiceConstant::ALL_TICKET_TYPE[$model->type];
                },
                'filter' => ServiceConstant::ALL_TICKET_TYPE
            ],
            [
                'attribute' => 'tripType',
                'value' => function ($model) {
                    return ServiceConstant::TRIP_TYPE[$model->tripType];
                },
                'filter' => ServiceConstant::TRIP_TYPE
            ],
            [
                'attribute' => 'bookedOnline',
                'value' => function ($model) {
                    return ServiceConstant::BOOKING_TYPE[$model->bookedOnline];
                },
                'filter' => ServiceConstant::BOOKING_TYPE
            ],
            [
                'attribute' => 'flightType',
                'value' => function ($model) {
                    return ServiceConstant::FLIGHT_TYPE[$model->flightType];
                },
                'filter' => ServiceConstant::FLIGHT_TYPE
            ],
            'seatClass',
            //'codeShare',
            'reference',
            'route',
            [
                'attribute' => 'numberOfSegment',
                'value' => 'numberOfSegment',
                'label' => 'Segments'
            ],
            'baseFare',
            'tax',
            'otherTax',
            [
                'attribute' => 'commissionReceived',
                'value' => 'commissionReceived',
                'label' => 'Commission'
            ],
            [
                'attribute' => 'incentiveReceived',
                'value' => 'incentiveReceived',
                'label' => 'Incentive'
            ],
            'serviceCharge',
            'ait',
            [
                'attribute' => 'quoteAmount',
                'value' => 'quoteAmount',
                'label' => 'Quote'
            ],
            [
                'attribute' => 'costOfSale',
                'value' => 'costOfSale',
                'label' => 'Cost'
            ],
            [
                'attribute' => 'receivedAmount',
                'value' => 'receivedAmount',
                'label' => 'Received'
            ],
            'netProfit',
            'paymentStatus',
            [
                'attribute' => 'isRefunded',
                'value' => function ($model) {
                    return GlobalConstant::YES_NO[$model->ticketRefund->isRefunded];
                },
                'filter' => GlobalConstant::YES_NO
            ],
            [
                'attribute' => 'refundStatus',
                'label' => 'Refund Status',
                'value' => function ($model) {
                    return $model->ticketRefund->refundStatus;
                },
                'filter' => ServiceConstant::REFUND_STATUS
            ],
            [
                'attribute' => 'refundType',
                'label' => 'Refund Type',
                'value' => function ($model) {
                    return $model->ticketRefund->refundType;
                },
                'filter' => ServiceConstant::REFUND_TYPE
            ],
            [
                'attribute' => 'refundMedium',
                'label' => 'Medium',
                'value' => function ($model) {
                    return $model->ticketRefund->refundMedium;
                },
                'filter' => ServiceConstant::REFUND_MEDIUM
            ],
            [
                'attribute' => 'refundMethod',
                'label' => 'Method',
                'value' => function ($model) {
                    return $model->ticketRefund->refundMethod;
                },
                'filter' => ServiceConstant::REFUND_METHOD
            ],
            [
                'attribute' => 'refundDate',
                'label' => 'Refund Date',
                'value' => function ($model) {
                    return $model->ticketRefund->refundDate;
                }
            ],
            [
                'attribute' => 'refundedAmount',
                'label' => 'Refunded Amount',
                'value' => function ($model) {
                    return $model->ticketRefund->refundedAmount;
                }
            ],
            [
                'attribute' => 'serviceCharge',
                'label' => 'Service Charge',
                'value' => function ($model) {
                    return $model->ticketRefund->serviceCharge;
                }
            ],
            [
                'attribute' => 'airlineRefundCharge',
                'label' => 'Airline Charge',
                'value' => function ($model) {
                    return $model->ticketRefund->airlineRefundCharge;
                }
            ],
            [
                'attribute' => 'supplierRefundCharge',
                'label' => 'Supplier Charge',
                'value' => function ($model) {
                    return $model->ticketRefund->supplierRefundCharge;
                }
            ],
            //'baggage',
            //'status',
            'createdBy',
            //'createdAt',
            'updatedBy',
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
                            'class' => 'btn btn-success btn-xs'
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
                            'class' => 'btn btn-danger btn-xs'
                        ]);
                    },
                ]
            ]
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/ticket/refund-list'], [
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
