<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\HotelInventory $model */
/** @var app\moduels\serviceInventory\models\HotelInventoryRoomDetail $model */
/** @var app\moduels\serviceInventory\models\HotelInventoryAmenity $model */

$this->title = Yii::t('app', 'Update Hotel Inventory: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotel Inventories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="hotel-inventory-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model2' => $model2,
        'model3' => $model3,
        'model4' => $model4,
        'roomDetail' => $roomDetail,
        'categories' => $categories,
        'roomTypes' => $roomTypes
    ]) ?>

</div>
