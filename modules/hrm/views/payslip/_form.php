<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Payslip $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="payslip-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employeeId')->textInput() ?>

    <?= $form->field($model, 'month')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'gross')->textInput() ?>

    <?= $form->field($model, 'tax')->textInput() ?>

    <?= $form->field($model, 'lateFine')->textInput() ?>

    <?= $form->field($model, 'totalAdjustment')->textInput() ?>

    <?= $form->field($model, 'totalDeduction')->textInput() ?>

    <?= $form->field($model, 'totalPaid')->textInput() ?>

    <?= $form->field($model, 'paymentMode')->dropDownList([ 'Bank Transfer' => 'Bank Transfer', 'Cheque' => 'Cheque', 'Cash' => 'Cash', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'processStatus')->textInput() ?>

    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

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
