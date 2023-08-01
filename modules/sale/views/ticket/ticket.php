<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\sale\components\ServiceConstant;
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
                <?= $form->field($model, "[$row]airlineId")->widget(Select2::classname(), Utilities::ajaxDropDown('airlineId', '/sale/airline/get-airlines', true, 'airlineId' . $row, 'airline', ($model->isNewRecord) ? [] : [$model->airlineId => $model->airline->name . ' | ' . $model->airline->code]))->label('Airline') ?>
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
            <!--<div class="col-md">
                <?php /*= $form->field($model, "[$row]serviceCharge")->textInput(['id' => 'serviceCharge' . $row, 'readOnly' => true]) */ ?>
            </div>-->
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($ticketSupplier, "[$row]supplierId")->widget(Select2::class, Utilities::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId' . $row, 'supplier', ($model->isNewRecord) ? [] : [$ticketSupplier->supplierId => $ticketSupplier->supplier->name . ' | ' . $ticketSupplier->supplier->company], $model->isNewRecord ? false : true))->label('Supplier') ?>
                <?= $form->field($ticketSupplier, "[$row]status")->hiddenInput(['id' => 'status' . $row, 'class' => 'status', 'value' => GlobalConstant::ACTIVE_STATUS])->label(false) ?>
                <?= $form->field($ticketSupplier, "[$row]paidAmount")->hiddenInput(['id' => 'paidAmount' . $row, 'class' => 'paidAmount', 'value' => 0])->label(false) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]providerId")->widget(Select2::class, Utilities::ajaxDropDown('providerId', '/sale/provider/get-providers', false, 'providerId' . $row, 'providerId' . $row, (!$model->isNewRecord && $model->provider) ? [$model->provider->id => $model->provider->name] : []))->label('Select GDS'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]type")->dropDownList(ServiceConstant::TICKET_TYPE_FOR_CREATE, ['disabled' => !$model->isNewRecord ? 'disabled' : false, 'class' => 'form-control type']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]motherTicketId")->widget(Select2::classname(), Utilities::ajaxDropDown('motherTicketId', 'get-mother-ticket', true, 'motherTicketId' . $row, 'motherTicket', (!$model->isNewRecord && $model->motherTicket) ? [$model->motherTicket => $model->motherTicket->eTicket . ' | ' . $model->motherTicket->pnrCode] : [], true))->label('Parent') ?>
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
                <?= $form->field($model, "[$row]paxName")->textInput(['id' => 'paxName' . $row, 'maxlength' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]paxType")->dropDownList(GlobalConstant::PAX_TYPE, ['id' => 'paxType' . $row]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]route")->textInput(['maxlength' => true, 'id' => 'route' . $row,]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]flightType")->dropDownList(ServiceConstant::FLIGHT_TYPE, ['id' => 'flightType' . $row]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]issueDate")->widget(DatePicker::class, WidgetHelper::getDatewidget('issueDate' . $row, 'issueDate', false, true))->label('Issue'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]departureDate")->widget(DatePicker::class, WidgetHelper::getDatewidget('departureDate' . $row))->label('Departure'); ?>
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
                <?= $form->field($model, "[$row]baseFare")->textInput(['id' => 'baseFare' . $row, 'type' => 'number', 'class' => 'form-control quoteCalculate baseFare']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]tax")->textInput(['id' => 'tax' . $row, 'type' => 'number', 'class' => 'form-control quoteCalculate tax']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]otherTax")->textInput(['id' => 'otherTax' . $row, 'type' => 'number', 'class' => 'form-control quoteCalculate otherTax']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]serviceCharge")->textInput(['maxlength' => true, 'id' => 'serviceCharge' . $row, 'type' => 'number', 'step' => 'any', 'class' => 'form-control quoteCalculate serviceCharge']) ?>
            </div>
            <div class="col-md">
                <label for="serviceCharge0">Discount</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <?= Html::dropDownList('discountType', null, ['Amount' => 'Amount', 'Percentage' => 'Percentage'], ['class' => 'form-control']) ?>
                    </div>
                    <input type="number" name="[$row]discount" class="form-control" aria-label="Text input with dropdown button">
                </div>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]quoteAmount")->textInput(['id' => 'quoteAmount' . $row, 'type' => 'number', 'step' => 'any', 'readOnly' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]seatClass")->dropDownList(ServiceConstant::SEAT_CLASS) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]tripType")->dropDownList(ServiceConstant::TRIP_TYPE, ['id' => 'tripType' . $row]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]bookedOnline")->dropDownList(GlobalConstant::BOOKING_TYPE, ['value' => 0]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]baggage")->textInput(['maxlength' => true]) ?>
            </div>
            <!--<div class="col-md">
                <? /*= $form->field($model, "[$row]codeShare")->dropDownList(GlobalConstant::YES_NO, ['value' => 0]) */ ?>
            </div>-->
            <div class="col-md">
                <?= $form->field($model, "[$row]referenceCommission")->textInput(['type' => 'number', 'maxlength' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]reference")->textInput(['maxlength' => true]) ?>
            </div>
            <?= $form->field($model, "[$row]customerId")->hiddenInput(['id' => 'customerId' . $row, 'class' => 'customerId'])->label(false) ?>
        </div>
        <?= (!$model->isNewRecord) ? Html::submitButton('<i class="fas fa-save"></i> Update', ['class' => 'btn btn-primary font-weight-bold float-right']) : '' ?>
    </div>
</div>