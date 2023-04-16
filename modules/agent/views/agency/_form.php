<?php

use app\components\Utilities;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\agent\models\Agency $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>
<div class="agency-form">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-gray-dark">
                    <?= Html::encode($this->title) ?>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'agentCode')->textInput(['value' => ($model->isNewRecord) ? Utilities::agentCode() : $model->agentCode, 'readOnly' => 'readOnly']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, "countryId")->widget(Select2::class, Utilities::ajaxDropDown('countryId', '/country/get-countries', true, 'countryId', 'country'))->label('Country') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, "cityId")->widget(DepDrop::class, Utilities::depDropConfigurationGenerate($model, 'cityId', 'countryId', '/city/get-city-by-country', ($model->city) ? [$model->cityId => $model->city->name] : [])) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'timeZone')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, "planId")->widget(Select2::class, Utilities::ajaxDropDown('planId', '/agent/plan/get-plans', true, 'planId', 'planId'))->label('Plan') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-gray-dark">
                    User Account Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12"><?= $form->field($signup, 'username') ?></div>
                        <div class="col-12"><?= $form->field($signup, 'email') ?></div>
                        <div class="col-12"><?= $form->field($signup, 'password')->passwordInput() ?></div>
                        <div class="col-12"><?= $form->field($signup, 'retypePassword')->passwordInput() ?></div>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>


</div>
