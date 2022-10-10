<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\holiday\Holiday */

$this->title = Yii::t('app', 'Create Holiday');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Holidays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-create">

    <?= $this->render('_form', [
        'model' => $model,
        'holidaySupplier' => $holidaySupplier,
        'holidayCategories' => $holidayCategories
    ]) ?>

</div>
