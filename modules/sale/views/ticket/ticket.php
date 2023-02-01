<?php

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\ticket\Ticket;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $image app\models\Voucher */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="card card-custom card-border mb-5" id="card<?= $row ?>">
    <div class="card-header bg-dark">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-paper-plane text-primary"></i></span>
            <h5 class="card-label" id="card-label-<?= $row ?>">
                Ticket <?= ($model->isNewRecord) ? ($row + 1) : ' - ' . $model->eTicket ?></h5>
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
                <?= $form->field($model, "[$row]airlineId")->widget(Select2::classname(), Helper::ajaxDropDown('airlineId', '/sale/airline/get-airlines', true, 'airlineId' . $row, 'airline', ($model->isNewRecord) ? [] : [$model->airlineId => $model->airline->name . ' | ' . $model->airline->code]))->label('Airline') ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]commission")->textInput(['id' => 'commission' . $row, 'readOnly' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]incentive")->textInput(['id' => 'incentive' . $row, 'readOnly' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]govTax")->textInput(['id' => 'govTax' . $row, 'readOnly' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]serviceCharge")->textInput(['id' => 'serviceCharge' . $row, 'readOnly' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($ticketSupplier, "[$row]supplierId")->widget(Select2::class, Helper::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId' . $row, 'supplier', ($model->isNewRecord) ? [] : [$ticketSupplier->supplierId => $ticketSupplier->supplier->name . ' | ' . $ticketSupplier->supplier->company], $model->isNewRecord ? false : true))->label('Supplier') ?>
                <?= $form->field($ticketSupplier, "[$row]status")->hiddenInput(['id' => 'status' . $row, 'class' => 'status', 'value' => GlobalConstant::ACTIVE_STATUS])->label(false) ?>
                <?= $form->field($ticketSupplier, "[$row]paidAmount")->hiddenInput(['id' => 'paidAmount' . $row, 'class' => 'paidAmount', 'value' => 0])->label(false) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]providerId")->widget(Select2::className(), Helper::ajaxDropDown('providerId', '/sale/provider/get-providers', true, 'providerId' . $row, 'providerId' . $row, (!$model->isNewRecord && $model->provider) ? [$model->provider->id => $model->provider->name] : []))->label('Select GDS'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]type")->dropDownList(GlobalConstant::TICKET_TYPE_FOR_CREATE, ['disabled' => !$model->isNewRecord ? 'disabled' : false, 'class' => 'form-control type']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]motherTicketId")->widget(Select2::classname(), Helper::ajaxDropDown('motherTicketId', 'get-mother-ticket', true, 'motherTicketId' . $row, 'motherTicket', (!$model->isNewRecord && $model->motherTicket) ? [$model->motherTicket => $model->motherTicket->eTicket . ' | ' . $model->motherTicket->pnrCode] : [], true))->label('Parent') ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]refundPolicy")->dropDownList(ServiceConstant::REFUND_POLICY) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]pnrCode")->textInput(['maxlength' => true])->label('PNR Code') ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]eTicket")->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]paxName")->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]paxType")->dropDownList(GlobalConstant::PAX_TYPE, ['prompt' => '']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]route")->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]flightType")->dropDownList(ServiceConstant::FLIGHT_TYPE) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]issueDate")->widget(DatePicker::className(), Helper::getDatewidget('issueDate' . $row))->label('Issue'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]departureDate")->widget(DatePicker::className(), Helper::getDatewidget('departureDate' . $row))->label('Departure'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]numberOfSegment")->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]refundPolicy")->dropDownList(ServiceConstant::REFUND_POLICY) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]baseFare")->textInput(['type' => 'number']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]tax")->textInput(['type' => 'number']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]otherTax")->textInput(['type' => 'number']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]quoteAmount")->textInput(['type' => 'number', 'step' => 'any']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]baggage")->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]seatClass")->dropDownList(ServiceConstant::SEAT_CLASS) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]tripType")->dropDownList(GlobalConstant::TRIP_TYPE) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]bookedOnline")->dropDownList(GlobalConstant::BOOKING_TYPE, ['value' => 0]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]codeShare")->dropDownList(GlobalConstant::YES_NO, ['value' => 0]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]reference")->textInput(['maxlength' => true]) ?>
            </div>
            <?= $form->field($model, "[$row]customerId")->hiddenInput(['id' => 'customerId' . $row, 'class' => 'customerId'])->label(false) ?>
        </div>
        <?= (!$model->isNewRecord) ? Html::submitButton('<i class="fas fa-save"></i>Update', ['class' => 'btn btn-light-primary font-weight-bold float-right']) : '' ?>
    </div>
</div>