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
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'motherId',
            'holidayId',
            'billId',
            'supplierId',
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
            'holidayCategoryId',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, HolidaySupplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
