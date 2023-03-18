<?php

use app\components\WidgetHelper;
use app\modules\hrm\components\HrmConstant;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeePayroll $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="employee-payroll-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-gray-dark">
                    <?= Html::encode($this->title) ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'employeeId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('employeeId', '/hrm/employee/get-employees', true, 'employeeId', 'employeeId', $model->isNewRecord ? [] : [$model->employee->id => $model->employee->firstName.' '.$model->employee->lastName])) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'gross')->textInput(['type' => 'number', 'value' => ($model->isNewRecord) ? 0 : $model->gross]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'tax')->textInput(['type' => 'number', 'value' => ($model->isNewRecord) ? 0 : $model->tax]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'paymentMode')->dropDownList(HrmConstant::PAYMENT_MODE, ['prompt' => '']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-gray-dark">
                    <?= Html::encode('Payroll Types') ?>
                </div>
                <div class="card-body">
                    <div class="bg-gray-light p-3">
                        <?php
                        foreach ($payrollList as $key => $item) {
                            ?>
                            <div class="row">
                                <div class="col-md">
                                    <?= $form->field($employeePayrollTypeDetail, "[$key]payrollTypeId")->dropDownList([$item['id'] => $item['name']])->label(!$key ? 'Payroll Type' : false) ?>
                                </div>
                                <div class="col-md">
                                    <label class="control-label">
                                        Category
                                    </label>
                                    <input name="" class="form-control" value="<?= HrmConstant::PAYROLL_CATEGORY[$item['category']] ?>" readonly="readonly">
                                </div>
                                <div class="col-md">
                                    <label  class="control-label">
                                        Amount Type
                                    </label>
                                    <input name="" class="form-control" value="<?= HrmConstant::AMOUNT_TYPE[$item['amountType']] ?>" readonly="readonly">
                                </div>
                                <div class="col-md">
                                    <label  class="control-label">
                                        Method
                                    </label>
                                    <input name="" class="form-control" value="<?= HrmConstant::CALCULATING_METHOD[$item['calculatingMethod']] ?>" readonly="readonly">
                                </div>
                                <div class="col-md">
                                    <label  class="control-label">
                                        Amount
                                    </label>
                                    <input name="" class="form-control" value="<?= $item['amount'] ?>" readonly="readonly">
                                </div>
                                <div class="col-md">
                                    <?= $form->field($employeePayrollTypeDetail, "[$key]amount")->textInput(['value' => $item['amount']])->label(!$key ? 'Amount' : false); ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="form-group float-right">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
