<?php

use app\modules\sale\models\holiday\HolidaySupplier;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
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
            ['class' => 'yii\grid\SerialColumn'],
            'motherId',
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
                'attribute' => 'holidayCategory',
                'value' => function ($model) {
                    return $model->country->name.'('.$model->country->code.')';
                },
                'label' => 'Category',
            ],
            [
                'attribute' => 'supplier',
                'value' => function ($model) {
                    return $model->supplier->company;
                },
                'label' => 'Supplier',
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
            'status',
            'description:ntext',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
