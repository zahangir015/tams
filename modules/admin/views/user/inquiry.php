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
    <div class="card-body login-card-body shadow rounded-lg">
        <?php $form = ActiveForm::begin(); ?>
        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-5">
            <h4 class="font-weight-bold text-center text-success">Inquiry</h4>
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
            <div class="col-lg-12 col-md-12">
                <?= $form->field($model, 'quire')->textarea(['maxlength' => true]) ?>
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
                    <?= Html::a('Get an Account!', ['/admin/user/account'], ['class' => 'btn btn-success btn-block']) ?>
                </div>
            </div>
        </div>
    </div>
</div>