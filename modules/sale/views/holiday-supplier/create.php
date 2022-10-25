<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\holiday\HolidaySupplier $model */

$this->title = Yii::t('app', 'Create Holiday Supplier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Holiday Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-supplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
