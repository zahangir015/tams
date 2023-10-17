<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountGroup $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="account-group-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'accountTypeId')->widget(Select2::class, Utilities::ajaxDropDown('accountTypeId', '/account/account-type/search', true, 'accountTypeId', 'accountTypeId', ($model->isNewRecord) ? [] : [$model->accountType->id => $model->accountType->name])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
