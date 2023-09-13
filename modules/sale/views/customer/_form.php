<?php

use app\components\GlobalConstant;
use app\components\WidgetHelper;
use app\modules\hrm\components\HrmConstant;
use kartik\date\DatePicker;
use app\components\Utilities;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Customer */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="customer-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'customerCode')->textInput(['maxlength' => true, 'readOnly' => true,'value' => ($model->isNewRecord) ? Utilities::customerCodeGenerator() : $model->customerCode]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'category')->dropDownList(GlobalConstant::CUSTOMER_CATEGORY, ['prompt' => '']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'starCategoryId')->dropDownList($starCategories, ['prompt' => '']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'creditModality')->dropDownList(GlobalConstant::YES_NO, ['prompt' => '']) ?>
                </div>
            </div>
            <div class="row">
                <?php
                if ($model->isNewRecord) {
                    ?>
                    <div class="col-md">
                        <?= $form->field($model, 'balance')->textInput(['type' => 'number', 'step' => 'any', 'value' => 0]) ?>
                    </div>
                    <?php
                }
                ?>
                <div class="col-md">
                    <?= $form->field($model, 'status')->dropdownList(GlobalConstant::DEFAULT_STATUS) ?>
                </div>
            </div>

            <p class="lead">Passport Details</p>

            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'passportNumber')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'issuedCountry')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'dateOfBirth')->widget(DatePicker::class, WidgetHelper::getDateWidget('dateOfBirth', 'dateOfBirth')) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'gender')->dropdownList(HrmConstant::GENDER_STRING) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'expireDate')->widget(DatePicker::class, WidgetHelper::getDateWidget('expireDate', 'expireDate')) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
