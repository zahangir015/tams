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
        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mt-5">
            <h4 class="font-weight-bold text-center text-success">Get Your Account Now</h4>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, "countryId")->widget(Select2::class, Utilities::ajaxDropDown('countryId', '/country/get-countries', true, 'countryId', 'country'))->label('Country') ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, "cityId")->widget(DepDrop::class, \app\components\WidgetHelper::depDropConfigurationGenerate($model, 'cityId', 'countryId', '/city/get-city-by-country', ($model->city) ? [$model->cityId => $model->city->name] : [])) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?= Html::submitButton('Send', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
        <div class="social-auth-links text-center mb-3">
            <p class="text-center">- OR -</p>
            <div class="row  mt-2">
                <div class="col-12 mb-2">
                    <?= Html::a('Sign In', ['/admin/user/login'], ['class' => 'btn btn-success btn-block']) ?>
                </div>
                <div class="col-12">
                    <?= Html::a('Inquire', ['/admin/user/inquiry'], ['class' => 'btn btn-success btn-block']) ?>
                </div>
            </div>
        </div>
    </div>
</div>