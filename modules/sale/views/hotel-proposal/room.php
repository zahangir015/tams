<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\sale\components\ServiceConstant;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */

?>
<div class="card card-custom card-border mb-5" id="card<?= $row ?>">
    <div class="card-header bg-success">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-paper-plane text-primary"></i></span>
            <h5 class="card-label" id="card-label-<?= $row ?>">Itinerary <?= ($row + 1) ?></h5>
        </div>
        <div class="card-toolbar float-right">
            <?php
            if ($model->isNewRecord) {
                ?>
                <a href="#" class="btn btn-danger btn-sm" onclick="removeRoom(<?= $row ?>)">
                    <span class="fa fa-times-circle"></span>
                </a>
                <?php
            } else {
                ?>
                <?= $form->field($roomDetails, "[$row]id")->hiddenInput(['maxlength' => true, 'value' => $roomDetails->id])->label(false); ?>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="card-body bg-gray-400">
        <div class="row">
            <div class="col-md">
                <?= $form->field($roomDetails, "[$row]roomTypeId")->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('roomTypeId', '')); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetails, "[$row]numberOfRoom")->textInput(['maxlength' => true]); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetails, "[$row]numberOfNight")->textInput(['maxlength' => true]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($roomDetails, "[$row]perNightPrice")->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('arrival' . $row, 'arrival' . $row, true, true)); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetails, "[$row]extraBed")->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('departure' . $row, 'departure' . $row, true, true)); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetails, "[$row]breakfast")->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('departure' . $row, 'departure' . $row, true, true)); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($roomDetails, "[$row]checkIn")->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('arrival' . $row, 'arrival' . $row, true, true)); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetails, "[$row]checkOut")->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('departure' . $row, 'departure' . $row, true, true)); ?>
            </div>
        </div>
    </div>
</div>