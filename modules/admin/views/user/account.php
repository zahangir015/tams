<?php

use app\components\Utilities;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

$this->title = Yii::t('rbac-admin', 'Account Request');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body login-card-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md">
            <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, "countryId")->widget(Select2::class, Utilities::ajaxDropDown('countryId', '/country/get-countries', true, 'countryId', 'country'))->label('Country') ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, "cityId")->widget(DepDrop::class, Utilities::depDropConfigurationGenerate($model, 'cityId', 'countryId', '/city/get-city-by-country', ($model->city) ? [$model->cityId => $model->city->name] : [])) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email']) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
