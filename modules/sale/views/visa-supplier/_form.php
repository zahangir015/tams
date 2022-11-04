<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\VisaSupplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visa-supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'motherVisaSupplierId')->textInput() ?>

    <?= $form->field($model, 'visaId')->textInput() ?>

    <?= $form->field($model, 'billId')->textInput() ?>

    <?= $form->field($model, 'countryId')->textInput() ?>

    <?= $form->field($model, 'supplierId')->textInput() ?>

    <?= $form->field($model, 'supplierRef')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paxName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'issueDate')->textInput() ?>

    <?= $form->field($model, 'refundRequestDate')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList([ 'New' => 'New', 'Refund' => 'Refund', 'Refund Requested' => 'Refund Requested', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'serviceDetails')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'unitPrice')->textInput() ?>

    <?= $form->field($model, 'costOfSale')->textInput() ?>

    <?= $form->field($model, 'securityDeposit')->textInput() ?>

    <?= $form->field($model, 'paidAmount')->textInput() ?>

    <?= $form->field($model, 'paymentStatus')->dropDownList([ 'Full Paid' => 'Full Paid', 'Partially Paid' => 'Partially Paid', 'Due' => 'Due', 'Refund Adjustment' => 'Refund Adjustment', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
