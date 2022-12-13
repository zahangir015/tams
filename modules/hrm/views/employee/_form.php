<?php

use app\modules\hrm\components\HrmConstant;
use kartik\daterange\DateRangePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\components\Helper;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Employee $model */
/** @var yii\bootstrap4\ActiveForm $form */
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
                        <?= $form->field($model, 'dateOfBirth')->widget(DateRangePicker::class, Helper::getDateWidget('dateOfBirth', 'dateOfBirth')) ?>
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
                    <div class="col-md"><?= $form->field($model, 'joiningDate')->textInput() ?></div>
                </div>
                <div class="row">
                    <div class="col-md"><?= $form->field($model, 'confirmationDate')->textInput() ?></div>
                    <div class="col-md"><?= $form->field($model, 'inProhibition')->textInput() ?></div>
                    <div class="col-md"><?= $form->field($model, 'jobCategory')->textInput(['maxlength' => true]) ?></div>
                    <div class="col-md">
                        <?= $form->field($model, 'reportTo')->widget(DepDrop::classname(), [
                            'data' => empty($model->reported) ? [] : [$model->reported->id => $model->reported->fullName],
                            'options' => ['placeholder' => 'Select Reported To...'],
                            'type' => DepDrop::TYPE_SELECT2,
                            'select2Options' => ['pluginOptions' => ['allowClear' => true], 'theme' => Select2::THEME_DEFAULT],
                            'pluginOptions' => [
                                'depends' => ['employeedesignation-departmentid'],
                                'initialize' => $model->isNewRecord ? false : true,
                                'url' => Url::to(['/employee/employee/employee-reported']),
                                'loadingText' => 'Loading Reported To ...',
                            ],
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <?= $form->field($designation, 'branchId')->dropDownList($branchList); ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($designation, 'departmentId')->dropDownList($departmentList);
                        ?>
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
                                'url' => Url::to(['/employee/employee/designation']),
                                'loadingText' => 'Loading designation ...',
                            ],
                        ]); ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
