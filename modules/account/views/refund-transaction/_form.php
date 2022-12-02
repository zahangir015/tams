<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\RefundTransaction $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="refund-transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'refId')->textInput() ?>

    <?= $form->field($model, 'refModel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payableAmount')->textInput() ?>

    <?= $form->field($model, 'receivableAmount')->textInput() ?>

    <?= $form->field($model, 'totalAmount')->textInput() ?>

    <?= $form->field($model, 'paymentStatus')->dropDownList([ 'Payable' => 'Payable', 'Receivable' => 'Receivable', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'adjustedAmount')->textInput() ?>

    <?= $form->field($model, 'isAdjusted')->textInput() ?>

    <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>

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
