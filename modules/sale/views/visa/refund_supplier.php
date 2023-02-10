<?php

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\sale\components\AccountConstant;
use app\modules\sale\models\ticket\Ticket;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="card card-custom card-border mb-5" id="card<?= $row ?>">
    <div class="card-header bg-dark">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-paper-plane text-primary"></i></span>
            <h5 class="card-label" id="card-label-<?= $row ?>">Visa Supplier <?= ($row + 1) ?></h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($visaSupplier, "[$row]supplierId")->dropdownList([$visaSupplier->supplier->id => $visaSupplier->supplier->company],['readOnly' => 'readOnly'])->label('Supplier') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($visaSupplier, "[$row]supplierRef")->textInput(['maxlength' => true]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($visaSupplier, "[$row]countryId")->dropdownList([$visaSupplier->country->id => $visaSupplier->country->name], ['readOnly' => 'readOnly'])->label('Country') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($visaSupplier, "[$row]paxName")->textInput(['maxlength' => true, 'readOnly' => 'readOnly']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($visaSupplier, "[$row]issueDate")->widget(DateRangePicker::class, Helper::dateFormat(false, true)); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($visaSupplier, "[$row]type")->dropdownList(AccountConstant::SERVICE_TYPE_FOR_CREATE, ['value' => AccountConstant::ALL_SERVICE_TYPE['New']]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($visaSupplier, "[$row]serviceDetails")->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($visaSupplier, "[$row]securityDeposit")->textInput(['type' => 'number', 'value' => $visaSupplier->isNewRecord ? 0 : $visaSupplier->securityDeposit, 'min' => 0, 'class' => 'form-control']) ?>
            </div>
        </div>
        <div class="row calcData">
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]quantity")->textInput(['type' => 'number', 'value' => $visaSupplier->isNewRecord ? 0 : $visaSupplier->quantity, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control quantity'])->label('Number Of Visa') ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]unitPrice")->textInput(['type' => 'number', 'value' => $visaSupplier->isNewRecord ? 0 : $visaSupplier->unitPrice, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control unitPrice'])->label('Per Visa Price') ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]costOfSale")->textInput(['type' => 'number', 'value' => $visaSupplier->isNewRecord ? 0 : $visaSupplier->costOfSale, 'onChange' => 'calculateCostOfSale()', 'min' => 0, 'class' => 'form-control costOfSale']) ?>
            </div>
        </div>

    </div>
</div>