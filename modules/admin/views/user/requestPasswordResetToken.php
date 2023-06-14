<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\PasswordResetRequest */

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body login-card-body shadow rounded-lg">
        <h3><?= Html::encode($this->title) ?></h3>
        <p>Please fill out your email. A link to reset password will be sent there.</p>
        <div class="row">
            <div class="col-lg">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form','options' => ['class' => 'form-group has-feedback'],]); ?>
                <?= $form->field($model, 'email') ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('rbac-admin', 'Send'), ['class' => 'btn btn-success btn-block']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="social-auth-links text-center mb-3">
            <p class="text-center">- OR -</p>
            <div class="row  mt-2">
                <div class="col-12 mb-2">
                    <?= Html::a('Sign In', ['/admin/user/login'], ['class' => 'btn btn-success btn-block']) ?>
                </div>
                <div class="col-12">
                    <?= Html::a('Inquire', ['/support/inquiry/'], ['class' => 'btn btn-success btn-block']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
