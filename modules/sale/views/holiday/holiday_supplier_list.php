<?php

use app\modules\sale\models\holiday\HolidaySupplier;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
/** @var yii\web\View $this */
/** @var app\modules\sale\models\holiday\HolidaySupplierSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Holiday Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-supplier-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'motherId',
            [
                'attribute' => 'holiday',
                'value' => function ($model) {
                    return $model->holiday->identificationNumber;
                },
                'label' => 'Holiday',
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
            [
                'attribute' => 'category',
                'value' => function ($model) {
                    return $model->category->name;
                },
                'label' => 'Category',
            ],
            'supplierRef',
            'issueDate',
            'departureDate',
            'refundRequestDate',
            'type',
            'serviceDetails',
            'quantity',
            'unitPrice',
            'costOfSale',
            'paidAmount',
            'paymentStatus',
            //'status',
            /*[
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, HolidaySupplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-info-circle"></i>', ['holiday-supplier-list', 'uid' => $model->uid], [
                            'title' => 'view',
                            'data-pjax' => '0',
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                ]
            ],*/
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/holiday/holiday-supplier-list'], [
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
