<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\Visa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'motherId')->textInput() ?>

    <?= $form->field($model, 'invoiceId')->textInput() ?>

    <?= $form->field($model, 'identificationNumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customerId')->textInput() ?>

    <?= $form->field($model, 'customerCategory')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'New' => 'New', 'Refund' => 'Refund', 'Refund Requested' => 'Refund Requested', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'issueDate')->textInput() ?>

    <?= $form->field($model, 'refundRequestDate')->textInput() ?>

    <?= $form->field($model, 'totalQuantity')->textInput() ?>

    <?= $form->field($model, 'processStatus')->textInput() ?>

    <?= $form->field($model, 'quoteAmount')->textInput() ?>

    <?= $form->field($model, 'costOfSale')->textInput() ?>

    <?= $form->field($model, 'netProfit')->textInput() ?>

    <?= $form->field($model, 'receivedAmount')->textInput() ?>

    <?= $form->field($model, 'paymentStatus')->dropDownList([ 'Full Paid' => 'Full Paid', 'Partially Paid' => 'Partially Paid', 'Due' => 'Due', 'Refund Adjustment' => 'Refund Adjustment', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'isOnlineBooked')->textInput() ?>

    <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'createdBy')->textInput() ?>

    <?= $form->field($model, 'createdAt')->textInput() ?>

    <?= $form->field($model, 'updatedBy')->textInput() ?>

    <?= $form->field($model, 'updatedAt')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
