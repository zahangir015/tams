<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\City $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="city-form">


    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-4"><?= $form->field($model, 'countryId')->widget(Select2::class, Utilities::ajaxDropDown('countryId', '/country/get-countries', true, 'countryId', 'company', ($model->isNewRecord) ? [] : [$model->country->id => $model->country->name.'('.$model->country->code.')'])) ?></div>
                <div class="col-4"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>
                <div class="col-4"><?= $form->field($model, 'status')->dropDownList(GlobalConstant::DEFAULT_STATUS) ?></div>
                <div class="form-group float-right">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
