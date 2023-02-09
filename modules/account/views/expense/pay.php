<?php

use app\components\GlobalConstant;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use app\components\Helper;

/** @var yii\web\View $this */
/** @var app\modules\account\models\Expense $model */

$this->title = Yii::t('app', 'Pay Expense: {identificationNumber}', [
    'identificationNumber' => $model->identificationNumber,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identificationNumber, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Payment');
?>
<div class="expense-payment">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'categoryId')->dropdownList([$model->category->id => $model->category->name], ['readOnly' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'subCategoryId')->dropdownList([$model->subCategory->id => $model->subCategory->name], ['readOnly' => true]); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'supplierId')->dropdownList(isset($model->supplier) ? [$model->supplier->id => $model->supplier->name] : [], ['readOnly' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'accruingMonth')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'timingOfExp')->dropDownList(['Monthly' => 'Monthly', 'Prepaid' => 'Prepaid', 'Accrued' => 'Accrued',], ['prompt' => '', 'value' => $model->timingOfExp, 'readOnly' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'totalCost')->textInput(['maxlength' => true, 'value' => $model->totalCost, 'readOnly' => true]) ?>
                </div>
            </div>
            <h4>Payment Details</h4>
            <hr>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($transaction, 'bankId')->widget(Select2::class, Helper::ajaxDropDown('bankId', '/account/bank-account/get-banks', 'true', 'bankId', 'bankId'))->label('Bank');
                    ?>
                </div>
                <div class="col-md">
                    <?= $form->field($transaction, 'reference')->textInput(['maxlength' => true])->label('Reference') ?>
                </div>
                <div class="col-md">
                    <?= $form->field($transaction, 'paidAmount')->textInput(['type' => 'number', 'value' => ($model->totalCost - $model->totalPaid), 'max' => ($model->totalCost - $model->totalPaid), 'min' => 1]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($transaction, 'paymentCharge')->textInput(['value' => 0]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($transaction, 'paymentMode')->dropdownList(GlobalConstant::PAYMENT_MODE, ['prompt' => '']); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($transaction, 'paymentDate')->widget(DatePicker::class, Helper::getDateWidget('date')); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Pay'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
