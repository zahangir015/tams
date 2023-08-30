<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $company app\models\Company */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="company-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($company, 'name')->textInput(['maxlength' => true, 'value' => $model->company]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($company, 'shortName')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($company, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($company, 'phone')->textInput(['maxlength' => true, 'value' => $model->phone]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($company, 'address')->textInput(['maxlength' => true, 'value' => $model->address]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($company, 'logo')->fileInput(['maxlength' => true, 'class' => 'form-control']) ?>
                </div>
                <?= $form->field($company, 'agencyId')->hiddenInput(['value' => $model->id])->label(false) ?>
            </div>
            <div class="form-group float-right">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
