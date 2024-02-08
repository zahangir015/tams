<?php

use app\components\Utilities;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

$this->title = Yii::t('rbac-admin', 'Account Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body login-card-body shadow rounded-lg">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-12 mb-5">
            <?= Html::a('Login', ['/admin/user/login'], ['class' => 'btn', 'name' => 'login-button', 'style' => 'background-color: #337abe; color: #ffffff; margin-left: 360px;']) ?>
        </div>
        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-3">
            <h4 class="font-weight-bold" style="color: #337abe; margin-left: auto; margin-right: auto">Get Your Account Now</h4>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('authorize name'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'designation')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('designation'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false)?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'company')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('company name'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, "countryId")->widget(Select2::class, Utilities::ajaxDropDown('countryId', '/country/get-countries', true, 'countryId', 'country',($model->country) ? [$model->countryId => $model->country->name] : []))->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, "cityId")->widget(DepDrop::class, \app\components\WidgetHelper::depDropConfigurationGenerate($model, 'cityId', 'countryId', '/city/get-city-by-country', ($model->city) ? [$model->cityId => $model->city->name] : []))->label(false) ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'phone')->textInput(['id' => 'mobile_code', 'maxlength' => true, 'placeholder' => $model->getAttributeLabel('mobile'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email', 'placeholder' => $model->getAttributeLabel('email'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= Html::submitButton('Send', ['class' => 'btn', 'name' => 'login-button', 'style' => 'background-color: #337abe; color: #ffffff; width: 100px;']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="social-auth-links text-center">
        <div class="row">
                <!-- <div class="col-12 mb-2">
                    <?= Html::a('Sign In', ['/admin/user/login'], ['class' => 'btn btn-success btn-block']) ?>
                </div> -->
                <div class="col-12">
                    <span>Or</span>
                    <?= Html::a('Inquire', ['/admin/user/inquiry'], ['class' => 'btn ml-2', 'style' => 'background-color: #337abe; color: #ffffff;']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
