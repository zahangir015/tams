<?php

use app\components\WidgetHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApprovalPolicy $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="leave-approval-policy-form">

    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'employeeId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('employeeId', '/hrm/employee/get-employees', true, 'employeeId', 'employeeId', $model->isNewRecord? [] : [$model->employeeId => $model->employee->firstName.' '.$model->employee->lastName])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'requestedTo')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('requestedTo', '/hrm/employee/get-employees', true, 'requestedTo', 'requestedTo', $model->isNewRecord? [] : [$model->requestedTo => $model->requestedEmployee->firstName.' '.$model->requestedEmployee->lastName])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'approvalLevel')->textInput(['type' => 'number']) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
