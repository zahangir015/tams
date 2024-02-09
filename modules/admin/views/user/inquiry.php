<?php

use app\components\Utilities;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

$this->title = Yii::t('rbac-admin', 'Inquiry');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body login-card-body shadow rounded-lg">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-12 mb-5">
            <?= Html::a('Login', ['/admin/user/login'], ['class' => 'btn', 'name' => 'login-button', 'style' => 'background-color: #337abe; color: #ffffff; margin-left: 360px;']) ?>
        </div>
        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-3">
            <h4 class="font-weight-bold" style="color: #337abe; margin-left: auto; margin-right: auto;">Inquire</h4>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('name'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email', 'placeholder' => $model->getAttributeLabel('email'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('mobile'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
            <div class="col-lg-6 col-md-6">
                <?= $form->field($model, 'company')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('company name'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-lg-12 col-md-12">
                <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <?= $form->field($model, 'quire')->textarea(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('type your query'), 'style' => 'background-color: #D6EADF; border: none;'])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?= Html::submitButton('Send Inquire', ['class' => 'btn', 'name' => 'login-button', 'style' => 'background-color: #337abe; color: #ffffff;']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
        <!-- <div class="social-auth-links text-center mb-3">
            <p class="text-center">- OR -</p>
            <div class="row  mt-2">
                <div class="col-12 mb-2">
                    <?= Html::a('Sign In', ['/admin/user/login'], ['class' => 'btn btn-success btn-block']) ?>
                </div>
                <div class="col-12">
                    <?= Html::a('Get an Account!', ['/admin/user/account'], ['class' => 'btn btn-success btn-block']) ?>
                </div>
            </div>
        </div> -->
    </div>
</div>
