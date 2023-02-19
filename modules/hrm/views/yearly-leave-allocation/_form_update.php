<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\YearlyLeaveAllocation $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="yearly-leave-allocation-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php
            $form = ActiveForm::begin();
            ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, "year")->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, "leaveTypeId")->dropdownList([$model->leaveType->id => $model->leaveType->name], ['value' => $model->leaveType->id]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, "numberOfDays")->textInput() ?>
                    <?= $form->field($model, "status")->hiddenInput(['value' => 1])->label(false) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
