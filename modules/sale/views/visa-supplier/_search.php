<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\VisaSupplierSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visa-supplier-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'motherVisaSupplierId') ?>

    <?= $form->field($model, 'visaId') ?>

    <?= $form->field($model, 'billId') ?>

    <?php // echo $form->field($model, 'countryId') ?>

    <?php // echo $form->field($model, 'supplierId') ?>

    <?php // echo $form->field($model, 'supplierRef') ?>

    <?php // echo $form->field($model, 'paxName') ?>

    <?php // echo $form->field($model, 'issueDate') ?>

    <?php // echo $form->field($model, 'refundRequestDate') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'serviceDetails') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'unitPrice') ?>

    <?php // echo $form->field($model, 'costOfSale') ?>

    <?php // echo $form->field($model, 'securityDeposit') ?>

    <?php // echo $form->field($model, 'paidAmount') ?>

    <?php // echo $form->field($model, 'paymentStatus') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'motherId') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
