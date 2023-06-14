<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

$this->title = Yii::t('rbac-admin', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id' => 'login-form']) ?>

<div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
    <h5 class="lead text-center">Sign in with</h5>
</div>

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
])->label(false)->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => '<div class="icheck-success">{input}{label}</div>',
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
        <?= Html::submitButton('Sign In', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
    </div>
</div>


<?php ActiveForm::end(); ?>

<!-- Email input -->
<!--<div class="form-outline mb-4">
    <input type="email" id="form3Example3" class="form-control form-control-lg"
           placeholder="Enter a valid email address"/>
    <label class="form-label" for="form3Example3">Email address</label>
</div>-->

<!-- Password input -->
<!--<div class="form-outline mb-3">
    <input type="password" id="form3Example4" class="form-control form-control-lg"
           placeholder="Enter password"/>
    <label class="form-label" for="form3Example4">Password</label>
</div>-->

<!--<div class="d-flex justify-content-between align-items-center">
    <div class="form-check mb-0">
        <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3"/>
        <label class="form-check-label" for="form2Example3">
            Remember me
        </label>
    </div>
    <a href="#!" class="text-body">Forgot password?</a>
</div>-->

<!--<div class="text-center text-lg-start mt-4 pt-2">
    <button type="button" class="btn btn-success btn-lg"
            style="padding-left: 2.5rem; padding-right: 2.5rem;">Login
    </button>
    <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="#!" class="link-danger">Register</a></p>
</div>-->
<!--<div class="card">
    <div class="card-body login-card-body shadow rounded-lg">



        <?php /*= $form->field($model, 'username', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])->label(false)->textInput(['placeholder' => $model->getAttributeLabel('username')]) */ ?>

        <?php /*= $form->field($model, 'password', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])->label(false)->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) */ ?>

        <div class="row">
            <div class="col-6">
                <?php /*= $form->field($model, 'rememberMe')->checkbox([
                    'template' => '<div class="icheck-success">{input}{label}</div>',
                    'labelOptions' => [
                        'class' => ''
                    ],
                    'uncheck' => null
                ]) */ ?>
            </div>
            <div class="col-6">
                <?php /*= Html::a('<i class="fa fa-key mr-2"></i> Forgot Password', ['user/request-password-reset'], ['class' => 'text-dark']) */ ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php /*= Html::submitButton('Sign In', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) */ ?>
            </div>
        </div>


        <?php /*ActiveForm::end(); */ ?>

        <div class="social-auth-links text-center mb-3">
            <p class="text-center">- OR -</p>
            <div class="row  mt-2">
                <div class="col-12 mb-2">
                    <?php /*= Html::a('Register a new membership', ['/admin/user/account'], ['class' => 'btn btn-success btn-block']) */ ?>
                </div>
                <div class="col-12">
                    <?php /*= Html::a('Inquire', ['/support/inquiry/'], ['class' => 'btn btn-success btn-block']) */ ?>
                </div>
            </div>
        </div>
    </div>
</div>-->
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
