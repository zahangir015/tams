<?php

use app\components\GlobalConstant;
use app\modules\sale\models\ticket\Ticket;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\ticket\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tickets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'motherTicketId',
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
                    return $model->provider->name;
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
                    return $model->customer->company. '(' . $model->customer->customerCode . ')';
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
            'paxType',
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
                'value' => 'type',
                'filter' => GlobalConstant::TICKET_TYPE_FOR_CREATE
            ],
            [
                'attribute' => 'tripType',
                'value' => 'tripType',
                'filter' => GlobalConstant::TRIP_TYPE
            ],
            [
                'attribute' => 'bookedOnline',
                'value' => 'bookedOnline',
                'filter' => GlobalConstant::BOOKING_TYPE
            ],
            [
                'attribute' => 'flightType',
                'value' => 'flightType',
                'filter' => GlobalConstant::FLIGHT_TYPE
            ],
            'seatClass',
            //'codeShare',
            'reference',
            'route',
            'numberOfSegment',
            'baseFare',
            'tax',
            'otherTax',
            //'commission',
            //'commissionReceived',
            //'incentive',
            //'incentiveReceived',
            'govTax',
            'serviceCharge',
            'ait',
            'quoteAmount',
            'receivedAmount',
            'paymentStatus',
            'costOfSale',
            'netProfit',
            //'baggage',
            //'status',
            'createdBy',
            //'createdAt',
            'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                }
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/ticket/create'], [
                        'title' => Yii::t('app', 'Add Category'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/ticket/index'], [
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
