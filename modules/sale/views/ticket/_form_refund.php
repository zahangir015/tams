<?php

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\sale\components\SaleConstant;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $ticketRefund \app\modules\sale\models\ticket\TicketRefund */
/* @var $form yii\bootstrap4\ActiveForm */

$this->registerJsFile(
    '@web/js/ticket-refund.js',
    ['depends' => [JqueryAsset::className()]]
);
?>
<div class="ticket-refund-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title"><?= Html::encode($this->title) ?></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <table class="table  table-bordered">
                            <tr>
                                <td><strong>Base Fare: </strong><?= $model->baseFare ?></td>
                                <td><strong>Tax: </strong><?= $model->tax ?></td>
                                <td><strong>Other Tax: </strong><?= $model->otherTax ?></td>
                            </tr>
                            <tr>
                                <td><strong>Commission: </strong><?= $model->commission ?></td>
                                <td><strong>Incentive: </strong><?= $model->incentive ?></td>
                                <td><strong>Commission Received: </strong><?= $model->commissionReceived ?></td>
                            </tr>
                            <tr>
                                <td><strong>Incentive Received: </strong><?= $model->incentiveReceived ?></td>
                                <td><strong>Cost of Sale: </strong> <?= $model->costOfSale ?>  </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">

                        <div class="col-md">
                            <?= $form->field($model, 'issueDate')->textInput(['disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'airlineId')->textInput(['value' => $model->airline->airlineName ?? '', 'disabled' => 'disabled'])->label('Airline') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'supplierId')->textInput(['value' => $model->ticketSupplier->supplier->name ?? '', 'disabled' => 'disabled'])->label('Supplier') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'customerId')->textInput(['value' => $model->customer->name ?? '', 'disabled' => 'disabled'])->label('Customer') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'paxName')->textInput(['disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'paxType')->dropDownList(['A' => 'A', 'C' => 'C', 'I' => 'I',], ['prompt' => '', 'disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'bookedOnline')->textInput(['disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'route')->textInput(['disabled' => 'disabled']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'seatClass')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'refundRequestDate')->widget(DatePicker::class, Helper::getDatewidget('refundRequestDate', 'refundRequestDate')); ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'pnrCode')->textInput(); ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'eTicket')->textInput(); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'baseFare')->textInput(['value' => 0]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'tax')->textInput(['value' => 0]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'otherTax')->textInput(['value' => 0]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'numberOfSegment')->textInput(['maxlength' => true, 'value' => 0]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($ticketRefund, 'supplierRefundCharge')->textInput(['type' => 'number', 'value' => $ticketRefund->supplierRefundCharge ?? $model->ticketSupplier->supplier->refundCharge, 'min' => 0, 'class' => 'quotePart form-control']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($ticketRefund, 'airlineRefundCharge')->textInput(['type' => 'number', 'value' => $ticketRefund->airlineRefundCharge ?? $model->airline->serviceCharge, 'min' => 0, 'class' => 'quotePart form-control']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($ticketRefund, 'refundCharge')->textInput(['type' => 'number', 'value' => $ticketRefund->isNewRecord ? 0 : $ticketRefund->refundCharge, 'min' => 0, 'class' => 'serviceCharge form-control']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'costOfSale')->textInput(['type' => 'number', 'value' => $ticketRefund->isNewRecord ? ($model->ticketSupplier->supplier->refundCharge + $model->airline->serviceCharge) : $model->costOfSale, 'readonly' => 'readonly']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'quoteAmount')->textInput(['type' => 'number', 'value' => $ticketRefund->isNewRecord ? ($model->ticketSupplier->supplier->refundCharge + $model->airline->serviceCharge) : $model->quoteAmount, 'class' => 'form-control']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'receivedAmount')->textInput(['type' => 'number', 'value' => $ticketRefund->isNewRecord ? $totalReceivedAmount : $model->receivedAmount, 'class' => 'form-control', 'readOnly' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'paymentStatus')->dropDownList(['Full Paid' => 'Full Paid', 'Partially Paid' => 'Partially Paid', 'Due' => 'Due'], ['disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'type')->textInput(['readOnly' => true, 'value' => 'Refund']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($ticketRefund, 'refundStatus')->dropDownList(SaleConstant::REFUND_STATUS, ['prompt' => 'Select refund status...']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($ticketRefund, 'refundMedium')->dropdownList(SaleConstant::REFUND_MEDIUM, ['prompt' => 'Select refund medium...']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($ticketRefund, 'refundMethod')->dropdownList(SaleConstant::REFUND_METHOD, ['prompt' => 'Select refund method...']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($ticketRefund, 'remarks')->textarea(['rows' => 3]) ?>
                            <?= $form->field($model, 'motherTicketId')->hiddenInput(['value' => $model->id])->label(false) ?>
                            <?= $form->field($model->ticketSupplier, "supplierId")->hiddenInput(['value' => $model->ticketSupplier->supplierId])->label(false) ?>
                            <?= $form->field($model->ticketSupplier, "status")->hiddenInput(['class' => 'status', 'value' => GlobalConstant::ACTIVE_STATUS])->label(false) ?>
                            <?= $form->field($model->ticketSupplier, "paidAmount")->hiddenInput(['class' => 'paidAmount', 'value' => $model->ticketSupplier->paidAmount])->label(false) ?>
                        </div>
                    </div>
                    <div class="form-group float-right mt-5">
                        <?= Html::submitButton(Yii::t('app', ($ticketRefund->isNewRecord) ? '<i class="ki ki-double-arrow-down icon-sm"></i>Save Refund Ticket' : '<i class="ki ki-double-arrow-down icon-sm"></i>Update Refund Ticket'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="col-md-6">
            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title"><? /*= ($ticketRefund->isNewRecord) ?  'Mother Ticket Information' : 'Ticket Information' */ ?></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <table class="table  table-bordered">
                            <tr>
                                <td><strong>Base Fare: </strong><? /*= $model->baseFare */ ?></td>
                                <td><strong>Tax: </strong><? /*= $model->tax */ ?></td>
                                <td><strong>Other Tax: </strong><? /*= $model->otherTax */ ?></td>
                            </tr>
                            <tr>
                                <td><strong>Commission: </strong><? /*= $model->commission */ ?></td>
                                <td><strong>Incentive: </strong><? /*= $model->incentive */ ?></td>
                                <td><strong>Commission Received: </strong><? /*= $model->commissionReceived */ ?></td>
                            </tr>
                            <tr>
                                <td><strong>Incentive Received: </strong><? /*= $model->incentiveReceived */ ?></td>
                                <td><strong>Cost of Sale: </strong> <? /*= $model->costOfSale */ ?>  </td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <? /*= $form->field($model, 'airlineId')->textInput(['value' => $model->airline->airlineName ?? '', 'disabled' => 'disabled'])->label('Airline') */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'supplierId')->textInput(['value' => $model->ticketSupplier->supplier->name ?? '', 'disabled' => 'disabled'])->label('Supplier') */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'customerId')->textInput(['value' => $model->customer->name ?? '', 'disabled' => 'disabled'])->label('Customer') */ ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <? /*= $form->field($model, 'issueDate')->textInput(['disabled' => 'disabled']) */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'eTicket')->textInput(['disabled' => 'disabled']); */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'paxName')->textInput(['disabled' => 'disabled']) */ ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <? /*= $form->field($model, 'paxType')->dropDownList(['A' => 'A', 'C' => 'C', 'I' => 'I',], ['prompt' => '', 'disabled' => 'disabled']) */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'pnrCode')->textInput(); */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'route')->textInput(['disabled' => 'disabled']) */ ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <? /*= $form->field($model, 'numberOfSegment')->textInput(['maxlength' => true, 'disabled' => 'disabled']) */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'seatClass')->textInput(['maxlength' => true, 'disabled' => 'disabled']) */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'paymentStatus')->dropDownList(['Full Paid' => 'Full Paid', 'Partially Paid' => 'Partially Paid', 'Due' => 'Due'], ['disabled' => 'disabled']) */ ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <? /*= $form->field($model, 'receivedAmount')->textInput(['disabled' => 'disabled']) */ ?>
                        </div>
                        <div class="col-md">
                            <? /*= $form->field($model, 'bookedOnline')->textInput(['disabled' => 'disabled']) */ ?>
                        </div>

                        <div class="col-md">
                            <? /*= $form->field($model, 'type')->textInput(['disabled' => 'disabled', 'id' => 'oldTicketType']) */ ?>
                            <? /*= $form->field($model, 'type')->hiddenInput(['value' => 'Refund'])->label(false) */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>

    <?php ActiveForm::end(); ?>
</div>
