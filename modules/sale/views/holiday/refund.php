<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Holiday */

use app\modules\sale\models\holiday\HolidayRefund;

$this->title = Yii::t('app', 'Create Refund Holiday');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Refund Holidays'), 'url' => ['refund-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-create">
    <?= $this->render('_form_refund', [
        'model' => $model,
        'motherHoliday' => $motherHoliday,
        'holidayRefund' => new HolidayRefund(),
        'holidayCategories' => $holidayCategories
    ]) ?>
</div>
