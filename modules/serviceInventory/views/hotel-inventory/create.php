<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\HotelInventory $model */
/** @var app\moduels\serviceInventory\models\HotelInventoryRoomDetail $model*/
/** @var app\moduels\serviceInventory\models\HotelInventoryAmenity $model*/

$this->title = Yii::t('app', 'Create Hotel Inventory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotel Inventories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-inventory-create">
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
