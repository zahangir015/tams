<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\agent\models\AgencyAccountRequest $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="agency-account-request-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>

                <div class="col-md"><?= $form->field($model, 'designation')->textInput(['maxlength' => true]) ?></div>

                <div class="col-md"><?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?></div>
            </div>
            <div class="row">
                <div class="col-md"><?= $form->field($model, 'countryId')->textInput() ?></div>

                <div class="col-md"><?= $form->field($model, 'cityId')->textInput() ?></div>
            </div>
            <div class="row">
                <div class="col-md"><?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?></div>

                <div class="col-md"><?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?></div>

                <div class="col-md"><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
