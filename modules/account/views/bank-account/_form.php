<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\BankAccount */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="bank-account-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'shortName')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'accountName')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'accountNumber')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'branch')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'routingNumber')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'swiftCode')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'paymentCharge')->textInput(['type' => 'number', 'min' => 0, 'step' => 'any', 'value' => $model->isNewRecord ? 0 : $model->paymentCharge]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'logo')->fileInput(['maxlength' => true, 'class' => 'form-control']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'balance')->textInput(['type' => 'number', 'min' => 0, 'step' => 'any', 'value' => 0]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'tag')->widget(Select2::classname(), Utilities::getTagWidget($model->tag))->label('Tag'); ?>
                </div>
            </div>
            <div class="row">

            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
