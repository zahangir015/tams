<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="account-type-form">

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
                    <?= $form->field($model, 'status')->dropdownList(GlobalConstant::DEFAULT_STATUS) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
