<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\sale\components\ServiceConstant;
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
        <div class="card-toolbar float-right">
            <?php
            if ($model->isNewRecord && $row) {
                ?>
                <a href="#" class="btn btn-danger btn-sm" onclick="remove(<?= $row ?>)">
                    <span class="fa fa-times-circle"></span>
                </a>
                <?php
            } else {
                ?>
                <?= $form->field($visaSupplier, "[$row]id")->hiddenInput(['maxlength' => true, 'value' => $visaSupplier->id])->label(false); ?>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]supplierId")->widget(Select2::class, Utilities::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId' . $row, 'supplier'))->label('Supplier') ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]supplierRef")->textInput(['maxlength' => true]); ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]countryId")->widget(Select2::class, Utilities::ajaxDropDown('countryId', '/country/get-countries', true, 'countryId' . $row, 'country'))->label('Country') ?>
            </div>
        </div>
        <div class="row calcData">
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]quantity")->textInput(['type' => 'number', 'value' => $visaSupplier->isNewRecord ? 0 : $visaSupplier->quantity, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control quantity'])->label('Number Of Visa') ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]unitPrice")->textInput(['type' => 'number', 'value' => $visaSupplier->isNewRecord ? 0 : $visaSupplier->unitPrice, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control unitPrice'])->label('Per Visa Quote') ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]costOfSale")->textInput(['type' => 'number', 'value' => $visaSupplier->isNewRecord ? 0 : $visaSupplier->costOfSale, 'onChange' => 'calculateCostOfSale()', 'min' => 0, 'class' => 'form-control costOfSale']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]issueDate")->widget(DatePicker::class, WidgetHelper::getDateWidget('issueDate' . $row, 'issueDate' . $row, false, true)); ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]type")->textInput(['readOnly' => 'readOnly', 'value' => ServiceConstant::ALL_SERVICE_TYPE['New']]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]paxName")->textInput(['maxlength' => true]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($visaSupplier, "[$row]securityDeposit")->textInput(['type' => 'number', 'value' => 0, 'min' => 0, 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($visaSupplier, "[$row]serviceDetails")->textInput() ?>
            </div>
        </div>
    </div>
</div>