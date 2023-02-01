<?php

use app\modules\sale\components\ServiceConstant;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\hotel\HotelSupplierSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Hotel Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-supplier-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'motherId',
            'motherId',
            'hotelName',
            'roomType',
            [
                'attribute' => 'hotel',
                'value' => function ($model) {
                    return $model->hotel->identificationNumber;
                },
                'label' => 'Hotel',
            ],
            [
                'attribute' => 'bill',
                'value' => function ($model) {
                    return $model->bill ? $model->bill->billNumber : null;
                },
                'label' => 'Bill',
            ],
            [
                'attribute' => 'supplier',
                'value' => function ($model) {
                    return $model->supplier->company;
                },
                'label' => 'Supplier',
            ],
            'supplierRef',
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
            /*[
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
            ],*/
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
                'attribute' => 'type',
                'value' => 'type',
                'filter' => ServiceConstant::ALL_SERVICE_TYPE
            ],
            'serviceDetails',
            'quantity',
            'unitPrice',
            'costOfSale',
            'paidAmount',
            'paymentStatus',
            //'status',
            'description:ntext',
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/hotel/create'], [
                        'title' => Yii::t('app', 'Add Category'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/hotel-supplier/index'], [
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
