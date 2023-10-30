<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\account\components\AccountConstant;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AdvancePayment $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="advance-payment-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-header">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'date')->widget(DatePicker::class, WidgetHelper::getDateWidget('date', 'date', false, true)) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, "refModel")->dropDownList(AccountConstant::WITHOUT_BANK_REF_MODEL, ['id' => 'refModel', 'class' => 'form-control refModel', 'prompt' => ''])->label('Reference Type'); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, "refId")->widget(DepDrop::class, Utilities::depDropConfigurationGenerate($model, 'refId', 'refModel', '/account/journal/get-reference'))->label('Reference') ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'identificationNumber')->textInput(['maxlength' => true, 'readOnly' => true, 'value' => ($model->isNewRecord) ? Utilities::advancePaymentIdentificationNumberGenerator() : $model->identificationNumber]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($transaction, 'bankId')->widget(Select2::class, [
                        'theme' => Select2::THEME_DEFAULT,
                        'data' => $bankList,
                        'options' => [
                            'id' => 'bankId',
                            'class' => 'form-control',
                            'placeholder' => 'Select a bank ...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Bank');
                    ?>
                </div>
                <div class="col-md">
                    <?= $form->field($transaction, 'reference')->textInput(['maxlength' => true])->label('Reference') ?>
                </div>
                <div class="col-md">
                    <?= $form->field($transaction, 'paidAmount')->textInput(['type' => 'number', 'min' => 1]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($transaction, 'paymentCharge')->textInput(['value' => 0]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($transaction, 'paymentMode')->dropdownList(GlobalConstant::PAYMENT_MODE, ['prompt' => '']); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($transaction, 'paymentDate')->widget(DatePicker::class, WidgetHelper::getDatewidget('paymentDate', 'paymentDate', false, true)); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
