<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeePayroll $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="employee-payroll-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employeeId')->textInput() ?>

    <?= $form->field($model, 'gross')->textInput() ?>

    <?= $form->field($model, 'tax')->textInput() ?>

    <?= $form->field($model, 'paymentMode')->dropDownList([ 'Bank Transfer' => 'Bank Transfer', 'Cheque' => 'Cheque', 'Cash' => 'Cash', ], ['prompt' => '']) ?>

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
