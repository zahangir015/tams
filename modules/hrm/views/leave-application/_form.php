<?php

use app\components\WidgetHelper;
use app\modules\hrm\components\HrmConstant;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\JqueryAsset;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApplication $model */
/** @var yii\bootstrap4\ActiveForm $form */

$this->registerJsFile(
    '@web/js/leave.js',
    ['depends' => [JqueryAsset::className()]]
);
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
                    <?= $form->field($model, 'employeeId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('employeeId', '/hrm/employee/get-employees', true, 'employeeId', 'employeeId', ($model->isNewRecord) ? [] : [$model->employeeId => $model->employee->firstName . ' ' . $model->employee->lastName])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'leaveTypeId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('leaveTypeId', '/hrm/leave-type/get-types', true, 'leaveTypeId', 'leaveTypeId', ($model->isNewRecord) ? [] : [$model->leaveTypeId => $model->leaveType->name])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'numberOfDays')->dropDownList(HrmConstant::NUMBER_OF_DAYS, ['prompt' => '']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'from')->widget(DatePicker::class, WidgetHelper::getDateWidget('from', 'from')) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'to')->widget(DatePicker::class, WidgetHelper::getDateWidget('to', 'to')) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'availableFrom')->textInput(['type' => 'time', 'readOnly' => 'readOnly']) ?>
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
