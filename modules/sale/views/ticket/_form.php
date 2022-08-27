<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Ticket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'motherTicketId')->textInput() ?>

    <?= $form->field($model, 'airlineId')->textInput() ?>

    <?= $form->field($model, 'providerId')->textInput() ?>

    <?= $form->field($model, 'invoiceId')->textInput() ?>

    <?= $form->field($model, 'customerId')->textInput() ?>

    <?= $form->field($model, 'customerCategory')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paxName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paxType')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'eTicket')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pnrCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'New' => 'New', 'Reissue' => 'Reissue', 'Refund' => 'Refund', 'EMD Voucher' => 'EMD Voucher', 'Refund Requested' => 'Refund Requested', 'Deportee' => 'Deportee', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'tripType')->dropDownList([ 'One Way' => 'One Way', 'Return' => 'Return', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'bookedOnline')->textInput() ?>

    <?= $form->field($model, 'flightType')->textInput() ?>

    <?= $form->field($model, 'seatClass')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codeShare')->textInput() ?>

    <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'issueDate')->textInput() ?>

    <?= $form->field($model, 'departureDate')->textInput() ?>

    <?= $form->field($model, 'refundRequestDate')->textInput() ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numberOfSegment')->textInput() ?>

    <?= $form->field($model, 'baseFare')->textInput() ?>

    <?= $form->field($model, 'tax')->textInput() ?>

    <?= $form->field($model, 'otherTax')->textInput() ?>

    <?= $form->field($model, 'commission')->textInput() ?>

    <?= $form->field($model, 'commissionReceived')->textInput() ?>

    <?= $form->field($model, 'incentive')->textInput() ?>

    <?= $form->field($model, 'incentiveReceived')->textInput() ?>

    <?= $form->field($model, 'govTax')->textInput() ?>

    <?= $form->field($model, 'serviceCharge')->textInput() ?>

    <?= $form->field($model, 'ait')->textInput() ?>

    <?= $form->field($model, 'quoteAmount')->textInput() ?>

    <?= $form->field($model, 'receivedAmount')->textInput() ?>

    <?= $form->field($model, 'paymentStatus')->dropDownList([ 'Full Paid' => 'Full Paid', 'Partially Paid' => 'Partially Paid', 'Due' => 'Due', 'Refund Adjustment' => 'Refund Adjustment', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'costOfSale')->textInput() ?>

    <?= $form->field($model, 'netProfit')->textInput() ?>

    <?= $form->field($model, 'baggage')->textInput(['maxlength' => true]) ?>

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
