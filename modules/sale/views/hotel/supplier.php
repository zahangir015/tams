<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\sale\components\ServiceConstant;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

?>
<div class="card card-custom card-border mb-5" id="card<?= $row ?>">
    <div class="card-header bg-dark">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-paper-plane text-primary"></i></span>
            <h5 class="card-label" id="card-label-<?= $row ?>">Hotel Supplier <?= ($row + 1) ?></h5>
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
                <?= $form->field($hotelSupplier, "[$row]supplierId")->widget(Select2::class, Utilities::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId' . $row, 'supplier', ($hotelSupplier->isNewRecord) ? [] : [$hotelSupplier->supplier->id => $hotelSupplier->supplier->company], !$hotelSupplier->isNewRecord))->label('Supplier') ?>
                <small id="passwordHelpBlock" class="form-text text-muted">
                    Add Supplier if not available. <?=  Html::a('Create Supplier', '/sale/supplier/create', ['target' => '_blank'])?>
                </small>
            </div>
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]supplierRef")->textInput(['maxlength' => true]); ?>
            </div>
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]issueDate")->widget(DatePicker::class, WidgetHelper::getDateWidget('issueDate'.$row, 'issue'.$row, false, true)); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]hotelName")->textInput() ?>
            </div>
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]roomType")->textInput() ?>
            </div>
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]type")->textInput(['readOnly' => 'readOnly', 'value' => ServiceConstant::ALL_SERVICE_TYPE['New']]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]serviceDetails")->textInput(['maxlength' => true]); ?>
            </div>
        </div>
        <div class="row calcData">
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]numberOfNights")->textInput(['type' => 'number', 'value' => $hotelSupplier->isNewrecord ? 0 : $hotelSupplier->numberOfNights, 'onChange' => "calculateQuoteAmount()", 'min' => 0, 'id' => 'nights'.$row, 'class' => 'form-control nights'])->label('Number Of Nights') ?>
            </div>
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]quantity")->textInput(['type' => 'number', 'value' => $hotelSupplier->isNewrecord ? 0 : $hotelSupplier->quantity, 'onChange' => "calculateQuoteAmount()", 'min' => 0, 'id' => 'roomQuantity'.$row])->label('Number Of Rooms') ?>
            </div>
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]unitPrice")->textInput(['type' => 'number', 'value' => $hotelSupplier->isNewrecord ? 0 : $hotelSupplier->unitPrice, 'onChange' => "calculateQuoteAmount()", 'min' => 0, 'id' => 'unitPrice'.$row])->label('Per Night Price') ?>
            </div>
            <div class="col-md">
                <?= $form->field($hotelSupplier, "[$row]costOfSale")->textInput(['type' => 'number', 'value' => $hotelSupplier->isNewrecord ? 0 : $hotelSupplier->costOfSale, 'onChange' => "calculateCostOfSale()", 'min' => 0, 'id' => 'costOfSale'.$row, 'class' => 'form-control costOfSale']) ?>
            </div>
        </div>
        <?php
        if (!$model->isNewRecord) {
            ?>
            <?= $form->field($hotelSupplier, "[$row]id")->hiddenInput(['maxlength' => true, 'value' => $hotelSupplier->id])->label(false); ?>
            <?php
        }
        ?>
    </div>
</div>