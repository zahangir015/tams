<?php

use app\components\GlobalConstant;
use app\components\Helper;
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
            <h5 class="card-label" id="card-label-<?= $row ?>">Holiday Supplier <?= ($row + 1) ?></h5>
        </div>
        <div class="card-toolbar float-right">
            <a href="#" class="btn btn-danger btn-sm" onclick="remove(<?= $row ?>)">
                    <span class="fa fa-times-circle">
                </span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]supplierId")->widget(Select2::class, Helper::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId' . $row, 'supplier', [$holidaySupplier->supplier->id => $holidaySupplier->supplier->company]))->label('Supplier') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]supplierRef")->textInput(['maxlength' => true]); ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]issueDate")->widget(DateRangePicker::class, Helper::dateFormat(false, true)); ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]departureDate")->widget(DateRangePicker::class, Helper::dateFormat(false, true)) ?>
            </div>
        </div>
        <div class="row calcData">
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]holidayCategoryId")->dropdownList($holidayCategories, ['prompt' => ''])->label('Category') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]quantity")->textInput(['type' => 'number', 'value' => $holidaySupplier->isNewrecord ? 0 : $holidaySupplier->quantity, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control quantity'])->label('Quantity') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]unitPrice")->textInput(['type' => 'number', 'value' => $holidaySupplier->isNewrecord ? 0 : $holidaySupplier->unitPrice, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control perServicePrice'])->label('Quote Rate') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]costOfSale")->textInput(['type' => 'number', 'value' => $holidaySupplier->isNewrecord ? 0 : $holidaySupplier->costOfSale, 'onChange' => 'calculateCostOfSale()', 'min' => 0, 'class' => 'form-control supplierCostOfSale']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]serviceDetails")->textInput(['maxlength' => true]); ?>
            </div>
        </div>
        <?= (!$model->isNewRecord) ? Html::submitButton('<i class="fas fa-save"></i>Update', ['class' => 'btn btn-light-primary font-weight-bold float-right']) : '' ?>
    </div>
</div>