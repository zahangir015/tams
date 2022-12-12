<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Employee $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'userId')->textInput() ?>

    <?= $form->field($model, 'reportTo')->textInput() ?>

    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fathersName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mothersName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dateOfBirth')->textInput() ?>

    <?= $form->field($model, 'gender')->textInput() ?>

    <?= $form->field($model, 'bloodGroup')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'maritalStatus')->textInput() ?>

    <?= $form->field($model, 'religion')->dropDownList([ 'Islam' => 'Islam', 'Hindu' => 'Hindu', 'Buddhist' => 'Buddhist', 'Christian' => 'Christian', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'nid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'officialId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'officialEmail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'officialPhone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanentAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'presentAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'personalEmail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'personalPhone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contactPersonsName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contactPersonsPhone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contactPersonsAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contactPersonsRelation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'joiningDate')->textInput() ?>

    <?= $form->field($model, 'confirmationDate')->textInput() ?>

    <?= $form->field($model, 'inProhibition')->textInput() ?>

    <?= $form->field($model, 'jobCategory')->textInput(['maxlength' => true]) ?>

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
