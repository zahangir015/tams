<?php

use app\components\GlobalConstant;
use app\modules\sale\components\ServiceConstant;
use kartik\daterange\DateRangePicker;
use kartik\editable\Editable;
use yii\helpers\Html;
use yii\helpers\Url;
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
            [
                'class' => 'kartik\grid\ActionColumn',
                'urlCreator' => function ($action, $model) {
                    return Url::to([$action, 'uid' => $model->uid]);
                },
                'template' => '{view} {update} {delete} {refund}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                //'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'refund' => function ($url, $model) {
                        if ($model->type === ServiceConstant::TYPE['Refund'] || $model->type === ServiceConstant::TYPE['Refund Requested']) {
                            return false;
                        }
                        return Html::a('<span class="fas fa-minus-square"></span>', ['/sale/ticket/refund', 'uid' => $model->uid], [
                            'title' => 'Refund',
                            'data-toggle' => 'tooltip'
                        ]);
                    },
                ]
            ],
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
                    return ($model->invoice) ? $model->invoice->invoiceNumber : null;
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
                'value' => function($model){
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
                'value' => function($model){
                    return ServiceConstant::ALL_TICKET_TYPE[$model->type];
                },
                'filter' => ServiceConstant::ALL_TICKET_TYPE
            ],
            [
                'attribute' => 'tripType',
                'value' => function($model){
                    return ServiceConstant::TRIP_TYPE[$model->tripType];
                },
                'filter' => ServiceConstant::TRIP_TYPE
            ],
            [
                'attribute' => 'bookedOnline',
                'value' => function($model){
                    return ServiceConstant::BOOKING_TYPE[$model->bookedOnline];
                },
                'filter' => ServiceConstant::BOOKING_TYPE
            ],
            'route',
            [
                'attribute' => 'flightType',
                'value' => function($model){
                    return ServiceConstant::FLIGHT_TYPE[$model->flightType];
                },
                'filter' => ServiceConstant::FLIGHT_TYPE
            ],
            /*[
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'flightStatus',
                'readonly' => function ($model, $key, $index, $widget) {
                    return false; // do not allow editing of inactive records
                },
                'editableOptions' => function ($model, $key, $index) {
                    return [
                        'header' => 'Flight Status',
                        'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                        //'data' => ServiceConstant::FLIGHT_STATUS,
                        'options' => [
                            'id' => $index . '_' . $model->id,
                        ],
                        'formOptions' => [
                            'id' => 'flightStatus_' . $model->id,
                            'action' => ['/sale/ticket/update-flight-status']
                        ]
                    ];
                },
                'hAlign' => 'right',
                'vAlign' => 'middle',
                'width' => '7%',
            ],*/
            [
                'attribute' => 'seatClass',
                'value' => function($model){
                    return ServiceConstant::SEAT_CLASS[$model->seatClass];
                },
                'filter' => ServiceConstant::SEAT_CLASS
            ],
            [
                'attribute' => 'refundPolicy',
                'value' => function($model){
                    return $model->refundPolicy ? ServiceConstant::REFUND_POLICY[$model->refundPolicy] : null;
                },
                'filter' => ServiceConstant::REFUND_POLICY
            ],
            'codeShare',
            'reference',
            [
                'attribute' => 'numberOfSegment',
                'value' => 'numberOfSegment',
                'label' => 'Segments',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'baseFare',
                'value' => 'baseFare',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'tax',
                'value' => 'tax',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'otherTax',
                'value' => 'otherTax',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'commissionReceived',
                'value' => 'commissionReceived',
                'label' => 'Commission',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'incentiveReceived',
                'value' => 'incentiveReceived',
                'label' => 'Incentive',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'serviceCharge',
                'value' => 'serviceCharge',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'ait',
                'value' => 'ait',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'quoteAmount',
                'value' => 'quoteAmount',
                'label' => 'Quote',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'costOfSale',
                'value' => 'costOfSale',
                'label' => 'Cost',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'discount',
                'value' => 'discount',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'receivedAmount',
                'value' => 'receivedAmount',
                'label' => 'Received',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'netProfit',
                'value' => 'netProfit',
                'label' => 'Profit',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            'paymentStatus',
            //'baggage',
            //'status',
            'createdBy',
            //'createdAt',
            'updatedBy',
            //'updatedAt',

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
        'showFooter' => true,
        'showPageSummary' => true,
        'panel' => [
            'heading' => '<i class="fas fa-list-alt"></i> ' . Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>
</div>
