<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ContraEntry $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="contra-entry-form">

    <div class="card">
        <div class="card-header bg-light">
            <div class="card-title"><?= Html::encode($this->title) ?></div>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'identificationNumber')->textInput(['maxlength' => true, 'readOnly' => true, 'value' => ($model->isNewRecord) ? Utilities::contraIdentificationNumber() : $model->identificationNumber]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'bankFrom')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('bankFrom', '/account/bank-account/get-banks', 'true', 'bankFrom', 'bankFrom', ($model->isNewRecord) ? [] : [$model->bankFrom => $model->transferredFrom->name])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'bankTo')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('bankTo', '/account/bank-account/get-banks', 'true', 'bankTo', 'bankTo', ($model->isNewRecord) ? [] : [$model->bankTo => $model->transferredTo->name])) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'amount')->textInput(['type' => 'number']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'paymentDate')->widget(\kartik\date\DatePicker::class, WidgetHelper::getDateWidget('paymentDate', 'paymentDate', false, true)) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
