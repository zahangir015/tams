<?php

use app\components\GlobalConstant;
use app\modules\sale\components\AccountConstant;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\bootstrap4\ActiveForm;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\hotel\Hotel */
/* @var $form yii\bootstrap4\ActiveForm */

$this->title = Yii::t('app', 'Refund Hotel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotel'), 'url' => ['refund-list']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(
    '@web/js/hotel.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="hotel-form">
    <?php $form = ActiveForm::begin(['class' => 'form']); ?>
    <div class="card card-custom mb-5 sticky-top">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-label">
                    Refund Hotel
                </h5>
            </div>
            <div class="card-toolbar float-right">
                <?= Html::submitButton(Yii::t('app', '<i class="fa fa-arrow-alt-circle-down"></i> Save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'refundRequestDate')->widget(DateRangePicker::class, Helper::dateFormat(false, true)) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'customerId')->dropdownList([$motherHotel->customer->id => $motherHotel->customer->company], ['readOnly' => 'readOnly'])->label('Customer') ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'issueDate')->textInput(['value' => $motherHotel->issueDate, 'readOnly' => 'readOnly']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'voucherNumber')->textInput(['maxlength' => true, 'value' => $motherHotel->voucherNumber, 'readOnly' => 'readOnly']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'reservationCode')->textInput(['maxlength' => true, 'value' => $motherHotel->reservationCode, 'readOnly' => 'readOnly']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-5">
        <div class="col-md-5 col-lg-4 order-md-last">
            <div class="card card-custom card-border mb-7">
                <div class="card-header bg-primary">
                    <div class="card-title">
                        <h5 class="card-label" id="card-label-0">Summary</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'identificationNumber')->textInput(['value' => Helper::hotelIdentificationNumber(), 'readOnly' => 'readOnly']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'costOfSale')->textInput(['type' => 'number', 'value' => $motherHotel->costOfSale, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Cost Of Sale') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'quoteAmount')->textInput(['type' => 'number', 'value' => $motherHotel->quoteAmount, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Quote Amount') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'netProfit')->textInput(['type' => 'number', 'value' => $motherHotel->netProfit, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Net Profit') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'totalNights')->textInput(['type' => 'number', 'value' => $motherHotel->totalNights, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Nights') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'route')->textInput(['maxlength' => true, 'value' => $motherHotel->route, 'readOnly' => 'readOnly']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'reference')->textInput(['maxlength' => true, 'value' => $motherHotel->reference, 'readOnly' => 'readOnly']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'checkInDate')->textInput(['maxlength' => true, 'value' => $motherHotel->checkInDate, 'readOnly' => 'readOnly']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'checkOutDate')->textInput(['maxlength' => true, 'value' => $motherHotel->checkOutDate, 'readOnly' => 'readOnly']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'freeCancellationDate')->textInput(['maxlength' => true, 'value' => $motherHotel->freeCancellationDate, 'readOnly' => 'readOnly']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'isRefundable')->dropDownList(GlobalConstant::YES_NO, ['value' => $motherHotel->isRefundable, 'readOnly' => 'readOnly']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($hotelRefund, 'refundStatus')->dropDownList(AccountConstant::OTHER_SERVICE_REFUND_STATUS, ['prompt' => 'Select refund status...']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($hotelRefund, 'refundMedium')->dropdownList(AccountConstant::REFUND_MEDIUM, ['prompt' => 'Select refund medium...']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'type')->dropdownList(AccountConstant::SERVICE_TYPE_FOR_CREATE, ['value' => AccountConstant::SERVICE_TYPE_FOR_CREATE['Refund'], 'readOnly' => 'readOnly'])->label('Type') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($hotelRefund, 'refundMethod')->dropdownList(AccountConstant::REFUND_METHOD, ['prompt' => 'Select refund method...']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($hotelRefund, 'remarks')->textarea() ?>
                            <?= $form->field($model, 'motherId')->hiddenInput(['value' => $motherHotel->id])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-lg-8">
            <div class="card-holder">
                <?php
                foreach ($motherHotel->hotelSuppliers as $key => $hotelSupplier) {
                    ?>
                    <?= $this->render('refund_supplier', ['row' => $key, 'model' => $model, 'hotelSupplier' => $hotelSupplier, 'form' => $form]); ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>