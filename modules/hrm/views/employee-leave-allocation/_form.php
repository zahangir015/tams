<?php

use app\components\WidgetHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveAllocation $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="employee-leave-allocation-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'employeeId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('employeeId', '/hrm/employee/get-employees', true, 'employeeId', 'employeeId', $model->isNewRecord ? [] : [$model->employee->id => $model->employee->firstName.' '.$model->employee->lastName])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'leaveTypeId')->dropDownList($types) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'year')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'totalDays')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'availedDays')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'remainingDays')->textInput(['type' => 'number']) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
