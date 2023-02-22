<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApplication $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="leave-application-form">

    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'employeeId')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'leaveTypeId')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'numberOfDays')->textInput() ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'from')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'to')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'availableFrom')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
