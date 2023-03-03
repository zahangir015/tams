<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Holiday */

$this->title = Yii::t('app', 'Update Holiday Refund: {id}', [
    'id' => $model->identificationNumber,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Refund Holidays'), 'url' => ['refund-list']];
$this->params['breadcrumbs'][] = ['label' => $model->identificationNumber, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="holiday-create">
    <?= $this->render('_form_refund_update', [
        'model' => $model,
        'holidayRefund' => $holidayRefund,
        'holidayCategories' => $holidayCategories
    ]) ?>
</div>
