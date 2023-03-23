<?php

use app\components\WidgetHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\JqueryAsset;
use yii\web\View;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Payslip $model */
/** @var yii\bootstrap4\ActiveForm $form */
$this->registerJs(
    "var payrollUrl = '" . Yii::$app->request->baseUrl . '/hrm/employee-payroll/get-employee-payroll' . "';var _csrf='" . Yii::$app->request->getCsrfToken() . "'; var attendanceUrl='" . Yii::$app->request->baseUrl . '/hrm/attendance/get-attendance-details' . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/payroll.js',
    ['depends' => [JqueryAsset::className()]]
);
?>

<div class="payslip-form">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-gray-dark">
                    <?= Html::encode($this->title) ?>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'employeeId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('employeeId', '/hrm/employee/get-employees', true, 'employeeId', 'employeeId', $model->isNewRecord ? [] : [$model->employee->id => $model->employee->firstName . ' ' . $model->employee->lastName])) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'month')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'year')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'gross')->textInput(['type' => 'number', 'readOnly' => 'readOnly']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'tax')->textInput(['type' => 'number', 'readOnly' => 'readOnly']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'lateFine')->textInput(['type' => 'number']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'totalAdjustment')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'totalDeduction')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'totalPaid')->textInput(['type' => 'number']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'paymentMode')->dropDownList(['Bank Transfer' => 'Bank Transfer', 'Cheque' => 'Cheque', 'Cash' => 'Cash',], ['prompt' => '']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'processStatus')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="form-group float-right">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-gray-dark">
                    Attendance History
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>Total Late In</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Total Short Working</td>
                            <td>0</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
