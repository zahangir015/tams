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
            <h5 class="card-label" id="card-label-<?= $row ?>">Visa Supplier <?= ($row + 1) ?></h5>
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
                <?= $form->field($visaSupplier, "[$row]supplierId")->widget(Select2::class, Helper::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId'.$row))->label('Supplier') ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]supplierRef")->textInput(['maxlength' => true]); ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]countryId")->dropDownList([], ['maxlength' => true]); ?>
            </div>
        </div>
        <div class="row calcData">
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]quantity")->textInput(['type' => 'number', 'value' => 0, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control quantity'])->label('Number Of Visa') ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]unitPrice")->textInput(['type' => 'number', 'value' => 0, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control unitPrice'])->label('Per Visa Price') ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]costOfSale")->textInput(['type' => 'number', 'value' => 0, 'onChange' => 'calculateCostOfSale()', 'min' => 0, 'class' => 'form-control costOfSale']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]paxName")->textInput(['maxlength' => true]); ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]securityDeposit")->textInput(['type' => 'number', 'value' => 0, 'min' => 0, 'class' => 'form-control']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($visaSupplier, "[$row]serviceDetails")->textInput(['maxlength' => true]); ?>
            </div>
        </div>
        <?= (!$model->isNewRecord) ? Html::submitButton('<i class="fas fa-save"></i>Update', ['class' => 'btn btn-light-primary font-weight-bold float-right']) : '' ?>
    </div>
</div>