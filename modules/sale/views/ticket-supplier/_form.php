<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\ticket\TicketSupplier $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ticket-supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ticketId')->textInput() ?>

    <?= $form->field($model, 'supplierId')->textInput() ?>

    <?= $form->field($model, 'airlineId')->textInput() ?>

    <?= $form->field($model, 'billId')->textInput() ?>

    <?= $form->field($model, 'issueDate')->textInput() ?>

    <?= $form->field($model, 'refundRequestDate')->textInput() ?>

    <?= $form->field($model, 'eTicket')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pnrCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'New' => 'New', 'Reissue' => 'Reissue', 'Refund' => 'Refund', 'EMD Voucher' => 'EMD Voucher', 'Refund Requested' => 'Refund Requested', 'Deportee' => 'Deportee', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'baseFare')->textInput() ?>

    <?= $form->field($model, 'tax')->textInput() ?>

    <?= $form->field($model, 'otherTax')->textInput() ?>

    <?= $form->field($model, 'costOfSale')->textInput() ?>

    <?= $form->field($model, 'paidAmount')->textInput() ?>

    <?= $form->field($model, 'paymentStatus')->dropDownList([ 'Full Paid' => 'Full Paid', 'Partially Paid' => 'Partially Paid', 'Due' => 'Due', 'Refund Adjustment' => 'Refund Adjustment', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'serviceCharge')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
