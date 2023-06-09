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
    <div class="card-body login-card-body">
        <h3><?= Html::encode($this->title) ?></h3>
        <p>Please fill out your email. A link to reset password will be sent there.</p>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form','options' => ['class' => 'form-group has-feedback'],]); ?>
                <?= $form->field($model, 'email') ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('rbac-admin', 'Send'), ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
