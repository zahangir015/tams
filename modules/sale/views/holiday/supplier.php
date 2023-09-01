<?php

use app\components\Utilities;
use app\modules\sale\components\ServiceConstant;
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
            <?php
            if ($model->isNewRecord) {
                ?>
                <a href="#" class="btn btn-danger btn-sm" onclick="remove(<?= $row ?>)">
                    <span class="fa fa-times-circle"></span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]supplierId")->widget(Select2::class, Utilities::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId' . $row, 'supplier', ($holidaySupplier->isNewRecord) ? [] : [$holidaySupplier->supplier->id => $holidaySupplier->supplier->company], !$holidaySupplier->isNewRecord))->label('Supplier') ?>
                <small id="passwordHelpBlock" class="form-text text-muted">
                    Add Supplier if not available. <?=  Html::a('Create Supplier', '/sale/supplier/create', ['target' => '_blank'])?>
                </small>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]supplierRef")->textInput(['maxlength' => true, 'readOnly' => !(bool)$holidaySupplier->isNewRecord]); ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]issueDate")->widget(\kartik\date\DatePicker::class, \app\components\WidgetHelper::getDateWidget('issueDate'.$row, 'issueDate', false, true)); ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]departureDate")->widget(DateRangePicker::class, Utilities::dateFormat(!(bool)$holidaySupplier->isNewRecord, true)) ?>
            </div>
        </div>
        <div class="row calcData">
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]holidayCategoryId")->dropdownList($holidayCategories, ['prompt' => '', 'disabled' => !(bool)$holidaySupplier->isNewRecord])->label('Category') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]quantity")->textInput(['type' => 'number', 'value' => $holidaySupplier->isNewrecord ? 0 : $holidaySupplier->quantity, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control quantity', 'readOnly' => !(bool)$holidaySupplier->isNewRecord])->label('Quantity') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]unitPrice")->textInput(['type' => 'number', 'value' => $holidaySupplier->isNewrecord ? 0 : $holidaySupplier->unitPrice, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control perServicePrice'])->label('Total Quote') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]costOfSale")->textInput(['type' => 'number', 'value' => $holidaySupplier->isNewrecord ? 0 : $holidaySupplier->costOfSale, 'onChange' => 'calculateCostOfSale()', 'min' => 0, 'class' => 'form-control supplierCostOfSale']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]title")->textInput() ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]type")->textInput(['readOnly' => 'readOnly', 'value' => ServiceConstant::ALL_SERVICE_TYPE['New']]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]serviceDetails")->textInput(['maxlength' => true]); ?>
            </div>
        </div>
        <?php
        if (!$model->isNewRecord) {
            ?>
            <?= $form->field($holidaySupplier, "[$row]id")->hiddenInput(['maxlength' => true, 'value' => $holidaySupplier->id])->label(false); ?>
            <?php
        }
        ?>
        <?= (!$model->isNewRecord) ? Html::submitButton('<i class="fas fa-save"></i>Update', ['class' => 'btn btn-light-primary font-weight-bold float-right']) : '' ?>
    </div>
</div>