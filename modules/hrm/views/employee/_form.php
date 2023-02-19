<?php

use app\components\GlobalConstant;
use app\modules\hrm\components\HrmConstant;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\components\Utilities;
use yii\helpers\Url;
use yii\web\JqueryAsset;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Employee $model */
/** @var yii\bootstrap4\ActiveForm $form */

$this->registerJsFile(
    '@web/js/employee.js',
    ['depends' => [JqueryAsset::className()]]
);
?>

<div class="employee-form">
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title"><?= Html::encode($this->title) ?></div>
        </div>
        <div class="card-body">
            <div class="employee-form">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form fv-plugins-bootstrap fv-plugins-framework', "id" => "kt_form"]]); ?>
                <h5 class="mb-10 font-weight-bold text-dark">Personal Details</h5>
                <hr>
                <div class="row">
                    <div class="col-md">
                        <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'fathersName')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'mothersName')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md"><?= $form->field($model, 'nid')->textInput(['maxlength' => true]) ?></div>
                </div>
                <div class="row mb-5">
                    <div class="col-md">
                        <?= $form->field($model, 'dateOfBirth')->widget(DatePicker::class, Utilities::getDateWidget('dateOfBirth', 'dateOfBirth form-control')) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'gender')->dropDownList(HrmConstant::GENDER) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'bloodGroup')->dropDownList(HrmConstant::BLOOD_GROUP, ['maxlength' => true]) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'maritalStatus')->dropDownList(HrmConstant::MARITAL_STATUS) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'religion')->dropDownList(HrmConstant::RELIGION, ['prompt' => '']) ?>
                    </div>
                </div>
                <h5 class="mb-10 font-weight-bold text-dark">Contact Details</h5>
                <hr>
                <div class="row">
                    <div class="col-md"><?= $form->field($model, 'permanentAddress')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"> <?= $form->field($model, 'presentAddress')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"> <?= $form->field($model, 'personalEmail')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"><?= $form->field($model, 'personalEmail')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"><?= $form->field($model, 'personalPhone')->textInput(['maxlength' => true]) ?></div>
                </div>
                <div class="row">
                    <div class="col-md"><?= $form->field($model, 'contactPersonsName')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"><?= $form->field($model, 'contactPersonsPhone')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"><?= $form->field($model, 'contactPersonsAddress')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"><?= $form->field($model, 'contactPersonsRelation')->textInput(['maxlength' => true]) ?></div>
                </div>
                <!--Official Details-->
                <h5 class="mb-10 font-weight-bold text-dark">Official Information</h5>
                <hr>
                <div class="row">
                    <div class="col-md"><?= $form->field($model, 'officialId')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"><?= $form->field($model, 'officialEmail')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"><?= $form->field($model, 'officialPhone')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md"><?= $form->field($model, 'joiningDate')->widget(DatePicker::class, Utilities::getDateWidget('joiningDate', 'joiningDate form-control')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md"><?= $form->field($model, 'confirmationDate')->widget(DatePicker::class, Utilities::getDateWidget('confirmationDate', 'confirmationDate form-control')) ?></div>
                    <div class="col-md"><?= $form->field($model, 'inProhibition')->dropDownList(GlobalConstant::YES_NO) ?></div>
                    <div class="col-md"><?= $form->field($model, 'jobCategory')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md">
                        <?= $form->field($designation, 'branchId')->dropDownList($branchList); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <?= $form->field($designation, 'departmentId')->dropDownList($departmentList); ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($designation, 'designationId')->widget(DepDrop::class, [
                            'data' => $designation->isNewRecord ? [] : [$model->employeeDesignation->designation->id => $model->employeeDesignation->designation->name],
                            'options' => ['placeholder' => 'Select designation...', 'id' => 'designationId',],
                            'type' => DepDrop::TYPE_SELECT2,
                            'select2Options' => ['pluginOptions' => ['allowClear' => true], 'theme' => Select2::THEME_DEFAULT],
                            'pluginOptions' => [
                                'depends' => ['employeedesignation-departmentid'],
                                'initialize' => $model->isNewRecord ? false : true,
                                'url' => Url::to(['/hrm/designation/get-designation-by-department']),
                                'loadingText' => 'Loading designation ...',
                            ],
                        ]); ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'reportTo')->widget(DepDrop::classname(), [
                            'data' => empty($model->reported) ? [] : [$model->reported->id => $model->reported->firstName . ' ' . $model->reported->lastName],
                            'options' => ['placeholder' => 'Select Reported To...'],
                            'type' => DepDrop::TYPE_SELECT2,
                            'select2Options' => ['pluginOptions' => ['allowClear' => true], 'theme' => Select2::THEME_DEFAULT],
                            'pluginOptions' => [
                                'depends' => ['employeedesignation-departmentid'],
                                'initialize' => $model->isNewRecord ? false : true,
                                'url' => Url::to(['/hrm/employee/get-employee-by-department']),
                                'loadingText' => 'Loading Reported To ...',
                            ],
                        ]); ?>
                    </div>
                </div>
                <?php
                if ($model->isNewRecord) {
                    ?>
                    <br>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="userCreate">
                                    <label class="custom-control-label" for="userCreate">Want to create a <b>User</b>
                                        for login?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="createUser">
                        <h5 class="mb-10 font-weight-bold text-dark">Login Credentials</h5>
                        <hr>
                        <div class="row">
                            <div class="col"><?= $form->field($signup, 'username') ?></div>
                            <div class="col"><?= $form->field($signup, 'email') ?></div>
                            <div class="col"><?= $form->field($signup, 'password')->passwordInput() ?></div>
                            <div class="col"><?= $form->field($signup, 'retypePassword')->passwordInput() ?></div>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
