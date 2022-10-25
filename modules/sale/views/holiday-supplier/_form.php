<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\holiday\HolidaySupplier $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="holiday-supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'holidayId')->textInput() ?>

    <?= $form->field($model, 'billId')->textInput() ?>

    <?= $form->field($model, 'supplierId')->textInput() ?>

    <?= $form->field($model, 'supplierRef')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'issueDate')->textInput() ?>

    <?= $form->field($model, 'departureDate')->textInput() ?>

    <?= $form->field($model, 'refundRequestDate')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList([ 'New' => 'New', 'Refund' => 'Refund', 'Refund Requested' => 'Refund Requested', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'serviceDetails')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'unitPrice')->textInput() ?>

    <?= $form->field($model, 'costOfSale')->textInput() ?>

    <?= $form->field($model, 'paidAmount')->textInput() ?>

    <?= $form->field($model, 'paymentStatus')->dropDownList([ 'Full Paid' => 'Full Paid', 'Partially Paid' => 'Partially Paid', 'Due' => 'Due', 'Refund Adjustment' => 'Refund Adjustment', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'holidayCategoryId')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'motherId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
