<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\StarCategory $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="star-category-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-3">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'level')->textInput() ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'pointRange')->textInput() ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'status')->dropDownList(GlobalConstant::DEFAULT_STATUS) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
