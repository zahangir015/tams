<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\holiday\HolidaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="holiday-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'motherId') ?>

    <?= $form->field($model, 'invoiceId') ?>

    <?= $form->field($model, 'holidayCategoryId') ?>

    <?php // echo $form->field($model, 'identificationNumber') ?>

    <?php // echo $form->field($model, 'customerId') ?>

    <?php // echo $form->field($model, 'customerCategory') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'issueDate') ?>

    <?php // echo $form->field($model, 'departureDate') ?>

    <?php // echo $form->field($model, 'refundRequestDate') ?>

    <?php // echo $form->field($model, 'quoteAmount') ?>

    <?php // echo $form->field($model, 'costOfSale') ?>

    <?php // echo $form->field($model, 'netProfit') ?>

    <?php // echo $form->field($model, 'receivedAmount') ?>

    <?php // echo $form->field($model, 'paymentStatus') ?>

    <?php // echo $form->field($model, 'isOnlineBooked') ?>

    <?php // echo $form->field($model, 'route') ?>

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
