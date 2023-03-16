<?php

use app\modules\hrm\components\HrmConstant;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\PayrollType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="payroll-type-form">
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
                    <?= $form->field($model, 'amountType')->dropDownList(HrmConstant::AMOUNT_TYPE, ['prompt' => '']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'calculatingMethod')->dropDownList(HrmConstant::CALCULATING_METHOD, ['prompt' => '']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'amount')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'category')->dropDownList(HrmConstant::PAYROLL_CATEGORY, ['prompt' => '']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'order')->textInput(['type' => 'number']) ?>
                </div>
            </div>

            <div class="form-group float-right">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
