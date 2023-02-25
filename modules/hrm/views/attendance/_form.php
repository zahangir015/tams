<?php

use app\components\WidgetHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Attendance $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="attendance-form">

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
                    <?= $form->field($model, 'shiftId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('shiftId', '/hrm/shift/get-shifts', true, 'shiftId', 'shiftId', ($model->isNewRecord) ? [] : [$model->shiftId => $model->shift->name])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'date')->widget(DatePicker::class, WidgetHelper::getDateWidget('date', 'date')) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'entry')->textInput(['type' => 'time']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'exit')->textInput(['type' => 'time']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
