<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\ServicePaymentTimeline */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-payment-timeline-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'refId')->textInput() ?>

    <?= $form->field($model, 'refModel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subRefId')->textInput() ?>

    <?= $form->field($model, 'subRefModel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paidAmount')->textInput() ?>

    <?= $form->field($model, 'dueAmount')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
