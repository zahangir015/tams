<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\sale\components\ServiceConstant;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\helpers\Url;

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
        <?php
        if ($row !== 0) {
            ?>
            <div class="card-toolbar float-right">
                <a href="#" class="btn btn-danger btn-sm" onclick="remove(<?= $row ?>)">
                    <span class="fa fa-times-circle">
                </span>
                </a>
            </div>
            <?php
        }
        ?>

    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <?= $form->field($ticketSupplier, "[$row]supplierId")->widget(Select2::class, Utilities::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId' . $row, 'supplier', ($model->isNewRecord) ? [] : [$ticketSupplier->supplierId => $ticketSupplier->supplier->name . ' | ' . $ticketSupplier->supplier->company], $model->isNewRecord ? false : true))->label('Supplier') ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]airlineId")->widget(DepDrop::class, [
                    'data' => $model->isNewRecord ? [] : [$model->airlineId => $model->airline->name . '(' . $model->airline->code . ')'],
                    'options' => ['placeholder' => 'Select Airline...', 'id' => 'airlineId' . $row, 'class' => 'airlineId'],
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true], 'theme' => Select2::THEME_DEFAULT],
                    'pluginOptions' => [
                        'depends' => ['supplierId' . $row],
                        'initialize' => !$model->isNewRecord,
                        'url' => Url::to(['/sale/airline/get-airline-by-supplier']),
                        'loadingText' => 'Loading airline ...',
                    ],
                ])->label('Airline'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]commission")->textInput(['class' => 'commission form-control', 'id' => 'commission' . $row, 'readOnly' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]incentive")->textInput(['class' => 'incentive form-control', 'id' => 'incentive' . $row, 'readOnly' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]govTax")->textInput(['class' => 'govTax form-control', 'id' => 'govTax' . $row, 'readOnly' => true]) ?>
            </div>
            <!--<div class="col-md">
                <?php /*= $form->field($model, "[$row]serviceCharge")->textInput(['id' => 'serviceCharge' . $row, 'readOnly' => true]) */ ?>
            </div>-->
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]providerId")->widget(Select2::class, Utilities::ajaxDropDown('providerId', '/sale/provider/get-providers', false, 'providerId' . $row, 'providerId', (!$model->isNewRecord && $model->provider) ? [$model->provider->id => $model->provider->name] : []))->label('Select GDS'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]pnrCode")->textInput(['maxlength' => true, 'class' => 'pnrCode form-control', 'id' => 'pnrCode' . $row])->label('PNR Code') ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]eTicket")->textInput(['maxlength' => true, 'class' => 'eTicket form-control', 'id' => 'eTicket' . $row]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]type")->dropDownList(ServiceConstant::TICKET_TYPE_FOR_CREATE, ['disabled' => !$model->isNewRecord ? 'disabled' : false, 'class' => 'form-control type', 'id' => 'type' . $row]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]motherTicketId")->widget(Select2::class, Utilities::ajaxDropDown('motherTicketId', 'get-mother-ticket', true, 'motherTicketId' . $row, 'motherTicket', (!$model->isNewRecord && $model->motherTicket) ? [$model->motherTicket => $model->motherTicket->eTicket . ' | ' . $model->motherTicket->pnrCode] : [], true))->label('Parent') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]paxName")->textInput(['id' => 'paxName' . $row, 'maxlength' => true]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]paxType")->dropDownList(GlobalConstant::PAX_TYPE, ['id' => 'paxType' . $row, 'class' => 'form-control paxType' . $row]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]route")->textInput(['maxlength' => true, 'id' => 'route' . $row, 'class' => 'form-control route']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]issueDate")->widget(DatePicker::class, WidgetHelper::getDatewidget('issueDate' . $row, 'issueDate', false, true))->label('Issue'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]departureDate")->widget(DatePicker::class, WidgetHelper::getDatewidget('departureDate' . $row, 'departureDate'))->label('Departure'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]baseFare")->textInput(['id' => 'baseFare' . $row, 'type' => 'number', 'class' => 'form-control calculateQuote baseFare', 'value' => $model->isNewRecord ? 0 : $model->baseFare]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]tax")->textInput(['id' => 'tax' . $row, 'type' => 'number', 'class' => 'form-control calculateQuote tax', 'value' => $model->isNewRecord ? 0 : $model->tax]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]otherTax")->textInput(['id' => 'otherTax' . $row, 'type' => 'number', 'class' => 'form-control calculateQuote otherTax', 'value' => $model->isNewRecord ? 0 : $model->otherTax]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]serviceCharge")->textInput(['maxlength' => true, 'id' => 'serviceCharge' . $row, 'type' => 'number', 'step' => 'any', 'class' => 'form-control calculateQuote serviceCharge', 'value' => $model->isNewRecord ? 0 : $model->serviceCharge]) ?>
            </div>
            <div class="col-md">
                <label for="serviceCharge0">Discount</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <?= Html::dropDownList('discountType', null, ['Amount' => 'Amount', 'Percentage' => 'Percentage'], ['class' => 'form-control calculateQuote discountType', 'id' => 'discountType' . $row]) ?>
                    </div>
                    <input type="number" name="Ticket[<?= $row ?>]discount" class="form-control calculateQuote discount"
                           aria-label="Text input with dropdown button" id='discount<?= $row ?>'
                           value="<?= $model->isNewRecord ? 0 : $model->discount ?>">
                </div>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]quoteAmount")->textInput(['id' => 'quoteAmount' . $row, 'class' => 'quoteAmount form-control', 'type' => 'number', 'step' => 'any', 'readOnly' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]numberOfSegment")->textInput(['maxlength' => true, 'class' => 'form-control numberOfSegment', 'id' => 'numberOfSegment' . $row]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]flightType")->dropDownList(ServiceConstant::FLIGHT_TYPE, ['id' => 'flightType' . $row, 'class' => 'form-control flightType']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]refundPolicy")->dropDownList(ServiceConstant::REFUND_POLICY, ['class' => 'form-control refundPolicy', 'id' => 'refundPolicy' . $row]) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]seatClass")->dropDownList(ServiceConstant::SEAT_CLASS, ['id' => 'seatClass' . $row, 'class' => 'form-control seatClass']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]tripType")->dropDownList(ServiceConstant::TRIP_TYPE, ['id' => 'tripType' . $row, 'class' => 'form-control tripType']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($model, "[$row]bookedOnline")->dropDownList(GlobalConstant::BOOKING_TYPE, ['value' => 0, 'class' => 'form-control bookedOnline']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]baggage")->textInput(['maxlength' => true, 'id' => 'baggage' . $row, 'class' => 'form-control baggage']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]referenceCommission")->textInput(['type' => 'number', 'maxlength' => true, 'class' => 'form-control referenceCommission']) ?>
            </div>
            <div class="col-md">
                <?= $form->field($model, "[$row]reference")->textInput(['maxlength' => true, 'class' => 'form-control reference']) ?>
            </div>
            <?= $form->field($model, "[$row]customerId")->hiddenInput(['id' => 'customerId' . $row, 'class' => 'customerId'])->label(false) ?>
            <?= $form->field($ticketSupplier, "[$row]status")->hiddenInput(['id' => 'status' . $row, 'class' => 'status', 'value' => GlobalConstant::ACTIVE_STATUS])->label(false) ?>
            <?= $form->field($ticketSupplier, "[$row]paidAmount")->hiddenInput(['id' => 'paidAmount' . $row, 'class' => 'paidAmount', 'value' => 0])->label(false) ?>
        </div>
    </div>
    <?= (!$model->isNewRecord) ? Html::submitButton('<i class="fas fa-save"></i> Update', ['class' => 'btn btn-primary font-weight-bold float-right']) : '' ?>
</div>