<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\HotelInventory $model */

$this->title = Yii::t('app', 'Create Hotel Inventory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotel Inventories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-inventory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
