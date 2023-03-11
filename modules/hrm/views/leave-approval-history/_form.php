<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApprovalHistory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="leave-approval-history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'leaveApplicationId')->textInput() ?>

    <?= $form->field($model, 'requestedTo')->textInput() ?>

    <?= $form->field($model, 'approvalLevel')->textInput() ?>

    <?= $form->field($model, 'approvalStatus')->dropDownList([ 'Pending' => 'Pending', 'Approved' => 'Approved', 'Cancelled' => 'Cancelled', ], ['prompt' => '']) ?>

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
