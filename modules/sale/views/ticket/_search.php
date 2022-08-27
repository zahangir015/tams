<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\search\TicketSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'motherTicketId') ?>

    <?= $form->field($model, 'airlineId') ?>

    <?= $form->field($model, 'providerId') ?>

    <?php // echo $form->field($model, 'invoiceId') ?>

    <?php // echo $form->field($model, 'customerId') ?>

    <?php // echo $form->field($model, 'customerCategory') ?>

    <?php // echo $form->field($model, 'paxName') ?>

    <?php // echo $form->field($model, 'paxType') ?>

    <?php // echo $form->field($model, 'eTicket') ?>

    <?php // echo $form->field($model, 'pnrCode') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'tripType') ?>

    <?php // echo $form->field($model, 'bookedOnline') ?>

    <?php // echo $form->field($model, 'flightType') ?>

    <?php // echo $form->field($model, 'seatClass') ?>

    <?php // echo $form->field($model, 'codeShare') ?>

    <?php // echo $form->field($model, 'reference') ?>

    <?php // echo $form->field($model, 'issueDate') ?>

    <?php // echo $form->field($model, 'departureDate') ?>

    <?php // echo $form->field($model, 'refundRequestDate') ?>

    <?php // echo $form->field($model, 'route') ?>

    <?php // echo $form->field($model, 'numberOfSegment') ?>

    <?php // echo $form->field($model, 'baseFare') ?>

    <?php // echo $form->field($model, 'tax') ?>

    <?php // echo $form->field($model, 'otherTax') ?>

    <?php // echo $form->field($model, 'commission') ?>

    <?php // echo $form->field($model, 'commissionReceived') ?>

    <?php // echo $form->field($model, 'incentive') ?>

    <?php // echo $form->field($model, 'incentiveReceived') ?>

    <?php // echo $form->field($model, 'govTax') ?>

    <?php // echo $form->field($model, 'serviceCharge') ?>

    <?php // echo $form->field($model, 'ait') ?>

    <?php // echo $form->field($model, 'quoteAmount') ?>

    <?php // echo $form->field($model, 'receivedAmount') ?>

    <?php // echo $form->field($model, 'paymentStatus') ?>

    <?php // echo $form->field($model, 'costOfSale') ?>

    <?php // echo $form->field($model, 'netProfit') ?>

    <?php // echo $form->field($model, 'baggage') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'createdAt') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <?php // echo $form->field($model, 'updatedAt') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
