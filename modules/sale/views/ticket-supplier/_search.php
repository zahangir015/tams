<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \app\modules\sale\models\ticket\TicketSupplierSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ticket-supplier-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'ticketId') ?>

    <?= $form->field($model, 'supplierId') ?>

    <?= $form->field($model, 'airlineId') ?>

    <?php // echo $form->field($model, 'billId') ?>

    <?php // echo $form->field($model, 'issueDate') ?>

    <?php // echo $form->field($model, 'refundRequestDate') ?>

    <?php // echo $form->field($model, 'eTicket') ?>

    <?php // echo $form->field($model, 'pnrCode') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'baseFare') ?>

    <?php // echo $form->field($model, 'tax') ?>

    <?php // echo $form->field($model, 'otherTax') ?>

    <?php // echo $form->field($model, 'costOfSale') ?>

    <?php // echo $form->field($model, 'paidAmount') ?>

    <?php // echo $form->field($model, 'paymentStatus') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'serviceCharge') ?>

    <?php // echo $form->field($model, 'motherId') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
