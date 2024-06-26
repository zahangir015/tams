<?php

use app\components\GlobalConstant;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\hotel\Hotel;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\hotel\HotelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hotels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-index">
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
                'attribute' => 'voucherNumber',
                'label' => 'voucher#',
            ],
            'reservationCode',
            [
                'attribute' => 'issueDate',
                'label' => 'Issue',
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
            [
                'attribute' => 'checkInDate',
                'label' => 'Check In',
            ],
            [
                'attribute' => 'checkOutDate',
                'label' => 'Check Out',
            ],
            [
                'attribute' => 'freeCancellationDate',
                'label' => 'Cancellation Date',
            ],
            'totalNights',
            'route',
            'isRefundable',
            [
                'attribute' => 'quoteAmount',
                'label' => 'Quote',
            ],
            [
                'attribute' => 'costOfSale',
                'label' => 'Cost',
            ],
            'netProfit',
            [
                'attribute' => 'receivedAmount',
                'label' => 'Received',
            ],
            'paymentStatus',
            [
                'attribute' => 'isOnlineBooked',
                'label' => 'Booked',
                'value' => function ($model) {
                    return GlobalConstant::BOOKING_TYPE[$model->isOnlineBooked];
                },
                'filter' => GlobalConstant::BOOKING_TYPE
            ],
            'reference',
            [
                'attribute' => 'isRefunded',
                'value' => function ($model) {
                    return  GlobalConstant::YES_NO[$model->hotelRefund->isRefunded];
                },
                'filter' => GlobalConstant::YES_NO
            ],
            /*[
                'attribute' => 'refundFromSupplierStatus',
                'label' => 'Refund From Supplier Status',
                'value' => function ($model) {
                    return $model->hotelRefund->refundFromSupplierStatus;
                }
            ],*/
            [
                'attribute' => 'refundStatus',
                'label' => 'Refund Status',
                'value' => function ($model) {
                    return $model->hotelRefund->refundStatus;
                },
                'filter' => ServiceConstant::REFUND_TYPE
            ],
            [
                'attribute' => 'refundMedium',
                'label' => 'Medium',
                'value' => function ($model) {
                    return $model->hotelRefund->refundMedium;
                },
                'filter' => ServiceConstant::REFUND_MEDIUM
            ],
            [
                'attribute' => 'refundMethod',
                'label' => 'Method',
                'value' => function ($model) {
                    return $model->hotelRefund->refundMethod;
                },
                'filter' => ServiceConstant::REFUND_METHOD
            ],
            [
                'attribute' => 'refundDate',
                'label' => 'Refund Date',
                'value' => function ($model) {
                    return $model->hotelRefund->refundDate;
                }
            ],
            [
                'attribute' => 'refundedAmount',
                'label' => 'Refunded Amount',
                'value' => function ($model) {
                    return $model->hotelRefund->refundedAmount;
                }
            ],
            [
                'attribute' => 'serviceCharge',
                'label' => 'Service Charge',
                'value' => function ($model) {
                    return $model->hotelRefund->serviceCharge;
                }
            ],
            [
                'attribute' => 'supplierRefundCharge',
                'label' => 'Supplier Charge',
                'value' => function ($model) {
                    return $model->hotelRefund->supplierRefundCharge;
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
                'template' => '{view} {update} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/hotel/create'], [
                        'title' => Yii::t('app', 'Add Category'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/hotel/refund-list'], [
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
