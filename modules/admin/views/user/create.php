<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \mdm\admin\models\form\Signup */

$this->title = Yii::t('rbac-admin', 'Create User');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body login-card-body">
        <div class="site-signup">
            <p>Please fill out the following fields to create:</p>
            <?= Html::errorSummary($model) ?>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <div class="row">
                <div class="col-6"><?= $form->field($model, 'username') ?></div>
                <div class="col-6"><?= $form->field($model, 'email') ?></div>
                <div class="col-6"><?= $form->field($model, 'password')->passwordInput() ?></div>
                <div class="col-6"><?= $form->field($model, 'retypePassword')->passwordInput() ?></div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('rbac-admin', 'Create User'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
