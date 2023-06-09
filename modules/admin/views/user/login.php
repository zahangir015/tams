<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

$this->title = Yii::t('rbac-admin', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg font-weight-bold text-green">Welcome To MY TRAMS</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>

        <?= $form->field($model, 'username', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])->label(false)->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => '<div class="icheck-primary">{input}{label}</div>',
                    'labelOptions' => [
                        'class' => ''
                    ],
                    'uncheck' => null
                ]) ?>
            </div>
            <div class="col-6">
                <?= Html::a('<i class="fa fa-key mr-2"></i> Forgot Password', ['user/request-password-reset'], ['class' => 'text-dark']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="social-auth-links text-center mb-3">

        </div>
    </div>
    <!-- /.login-card-body -->
</div>
<!--<div class="site-login">
    <h1><? /*= Html::encode($this->title) */ ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php /*$form = ActiveForm::begin(['id' => 'login-form']); */ ?>
                <? /*= $form->field($model, 'username') */ ?>
                <? /*= $form->field($model, 'password')->passwordInput() */ ?>
                <? /*= $form->field($model, 'rememberMe')->checkbox() */ ?>
                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <? /*= Html::a('reset it', ['user/request-password-reset']) */ ?>.
                    For new user you can <? /*= Html::a('signup', ['user/signup']) */ ?>.
                </div>
                <div class="form-group">
                    <? /*= Html::submitButton(Yii::t('rbac-admin', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) */ ?>
                </div>
            <?php /*ActiveForm::end(); */ ?>
        </div>
    </div>
</div>-->
