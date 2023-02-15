<?php

use app\modules\sale\components\ServiceConstant;
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
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]supplierId")->dropdownList([$holidaySupplier->supplier->id => $holidaySupplier->supplier->company], ['readOnly' => 'readOnly'])->label('Supplier') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]supplierRef")->textInput(['maxlength' => true, 'readOnly' => 'readOnly']); ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]issueDate")->textInput(['value' => $holidaySupplier->issueDate, 'readOnly' => 'readOnly']); ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]departureDate")->textInput(['maxlength' => true, 'readOnly' => 'readOnly']) ?>
            </div>
        </div>
        <div class="row calcData">
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]holidayCategoryId")->dropdownList($holidayCategories, ['prompt' => '', 'readOnly' => 'readOnly'])->label('Category') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]quantity")->textInput(['type' => 'number', 'value' => $holidaySupplier->quantity, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control quantity', 'readOnly' => 'readOnly'])->label('Quantity') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]unitPrice")->textInput(['type' => 'number', 'value' => $holidaySupplier->unitPrice, 'onChange' => 'calculateQuoteAmount()', 'min' => 0, 'class' => 'form-control perServicePrice'])->label('Quote Rate') ?>
            </div>
            <div class="col-md">
                <?= $form->field($holidaySupplier, "[$row]costOfSale")->textInput(['type' => 'number', 'value' => $holidaySupplier->costOfSale, 'onChange' => 'calculateCostOfSale()', 'min' => 0, 'class' => 'form-control supplierCostOfSale']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($holidaySupplier, "[$row]type")->dropdownList(ServiceConstant::SERVICE_TYPE_FOR_CREATE, ['value' => $holidaySupplier->type]) ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($holidaySupplier, "[$row]serviceDetails")->textInput(['maxlength' => true]); ?>
                <?= $form->field($holidaySupplier, "[$row]motherId")->hiddenInput(['maxlength' => true, 'value' => $holidaySupplier->id])->label(false) ?>
            </div>
        </div>
        <?= (!$model->isNewRecord) ? Html::submitButton('<i class="fas fa-save"></i>Update', ['class' => 'btn btn-light-primary font-weight-bold float-right']) : '' ?>
    </div>
</div>