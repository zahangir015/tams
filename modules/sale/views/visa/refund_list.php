<?php

use app\components\GlobalConstant;
use app\modules\sale\components\ServiceConstant;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\visa\VisaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Visa Refund List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visa-index" id="visaRefund">
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
                'filter' => ServiceConstant::ALL_SERVICE_TYPE
            ],
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
            'totalQuantity',
            'processStatus',
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
            [
                'attribute' => 'isOnlineBooked',
                'label' => 'Booked',
                'value' => function ($model) {
                    return GlobalConstant::BOOKING_TYPE[$model->isOnlineBooked];
                },
                'filter' => GlobalConstant::BOOKING_TYPE
            ],
            'reference',
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
                'template' => '{view} {update} {delete} {refund}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'refund' => function ($url, $model) {
                        if ($model->type === ServiceConstant::TYPE['Refund'] || $model->type === ServiceConstant::TYPE['Refund Requested']) {
                            return false;
                        }
                        return Html::a('<span class="fas fa-minus-square"></span>', ['/sale/visa/refund', 'uid' => $model->uid], [
                            'title' => 'Refund',
                            'data-toggle' => 'tooltip'
                        ]);
                    },
                ]
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-greater-than" id="icon"></i>',[''],[
                        'title' => Yii::t('app','View More'),
                        'class' => 'btn btn-primary',
                        'id' => 'btnMore'
                    ]).' '.
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/visa/create'], [
                        'title' => Yii::t('app', 'Add Category'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/visa/index'], [
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
<script>
    let visaRefund = document.getElementById("visaRefund");
    let btn = document.getElementById("btnMore");
    let icon = document.getElementById("icon");
    visaRefund.style.width = "500px";
    btn.addEventListener("click",function(e){
        e.preventDefault();
        if(icon.classList.contains("fa-greater-than"))
        {
            icon.classList.remove("fa-greater-than");
            icon.classList.add("fa-less-than");
            visaRefund.style.width = "auto";
            btn.title = "View Less";
        }
        else if(icon.classList.contains("fa-less-than"))
        {
            icon.classList.remove("fa-less-than");
            icon.classList.add("fa-greater-than");
            visaRefund.style.width = "500px";
            btn.title = "View More";
        }
    })
</script>
