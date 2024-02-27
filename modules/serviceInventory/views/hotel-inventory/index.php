<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\serviceInventory\models\HotelInventory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\HotelInventorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Hotel Inventories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-inventory-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Hotel Inventory'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'supplierId',
            'hotelName',
            'hotelAddress',
            //'countryId',
            //'cityId',
            //'hotelCategoryId',
            //'status',
            //'createdAt',
            //'createdBy',
            //'updatedAt',
            //'updatedBy',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, HotelInventory $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
