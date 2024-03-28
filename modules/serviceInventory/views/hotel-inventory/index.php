<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\serviceInventory\models\HotelInventory;
use app\modules\serviceInventory\models\HotelInventoryRoomDetail;
use app\modules\serviceInventory\models\HotelInventoryAmenity;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\HotelInventorySearch $searchModel */
/** @var app\modules\serviceInventory\models\HotelInventoryRoomDetailSearch */
/** @var app\modules\serviceInventory\models\HotelInventoryAmenitySearch */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Hotel Inventories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-inventory-index">
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, HotelInventory $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                 },
                 'width' => '150px',
                 'template' => '{view} {edit} {delete}',
                 'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                 'buttons' => Utilities::getBasicActionColumnArray()
            ],
            'id',
            'uid',
            'supplierId',
            'hotelName',
            'hotelAddress',
            'countryId',
            'cityId',
            'hotelCategoryId',
            'status',
            // 'roomTypeId',
            // 'meal',
            // 'extraBed',
            // 'numberOfRoom',
            // 'isAvailable',
            // 'cancelationPolicy',
            // 'perNightCost',
            // 'currency',
            // 'perNightSelling',
            // 'currency',
            // 'priceValidity',
            // 'transfer',
            // 'transferDetails',
            // 'hotelInventoryId',
            // 'amenityId',
            //'createdAt',
            //'createdBy',
            //'updatedAt',
            //'updatedBy',
        ],
        'toolbar' => WidgetHelper::kartikToolBar(),
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<i class="fas fa-list-alt"></i>'.Html::encode($this->title),
            'type' => GridView::TYPE_LIGHT
        ]
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'filterModel' => $searchModel2,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, HotelInventory $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                 },
                 'width' => '150px',
                 'template' => '{view} {edit} {delete}',
                 'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                 'buttons' => Utilities::getBasicActionColumnArray()
            ],
            'roomTypeId',
            'meal',
            'extraBed',
            'numberOfRoom',
            'isAvailable',
            'cancelationPolicy',
            'perNightCost',
            'currency',
            'perNightSelling',
            'currency',
            'priceValidity',
            'transfer',
            'transferDetails'
        ],
        'toolbar' => WidgetHelper::kartikToolBar(),
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider3,
        'filterModel' => $searchModel3,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, HotelInventory $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                 },
                 'width' => '150px',
                 'template' => '{view} {edit} {delete}',
                 'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                 'buttons' => Utilities::getBasicActionColumnArray()
            ],
            'hotelInventoryRoomDetailId',
            'amenityId'
        ],
        'toolbar' => WidgetHelper::kartikToolBar(),
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
