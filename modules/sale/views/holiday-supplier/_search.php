<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\holiday\HolidaySupplierSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="holiday-supplier-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'holidayId') ?>

    <?= $form->field($model, 'billId') ?>

    <?= $form->field($model, 'supplierId') ?>

    <?php // echo $form->field($model, 'supplierRef') ?>

    <?php // echo $form->field($model, 'issueDate') ?>

    <?php // echo $form->field($model, 'departureDate') ?>

    <?php // echo $form->field($model, 'refundRequestDate') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'serviceDetails') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'unitPrice') ?>

    <?php // echo $form->field($model, 'costOfSale') ?>

    <?php // echo $form->field($model, 'paidAmount') ?>

    <?php // echo $form->field($model, 'paymentStatus') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'holidayCategoryId') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'motherId') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
