<?php

use app\components\GlobalConstant;
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
            <h5 class="card-label" id="card-label-<?= $row ?>">Room Detail <?= ($row + 1) ?></h5>
        </div>
        <div class="card-toolbar float-right">
            <?php
            if ($model->isNewRecord && $row) {
                ?>
                <a href="#" class="btn btn-danger btn-sm" onclick="removeRoom(<?= $row ?>)">
                    <span class="fa fa-times-circle"></span>
                </a>
                <?php
            } else {
                ?>
                <?= $form->field($roomDetail, "[$row]id")->hiddenInput(['maxlength' => true, 'value' => $roomDetail->id])->label(false); ?>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="card-body bg-gray-400">
        <div class="row">
            <div class="col-md">
                <?= $form->field($roomDetail, "[$row]roomTypeId")->dropDownList($roomTypes)->label('Room Type'); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetail, "[$row]numberOfRoom")->textInput(['maxlength' => true, 'type' => 'number']); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetail, "[$row]numberOfNight")->textInput(['maxlength' => true, 'type' => 'number']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($roomDetail, "[$row]perNightPrice")->textInput(['maxlength' => true, 'type' => 'number']); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetail, "[$row]extraBed")->dropdownList(GlobalConstant::YES_NO); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetail, "[$row]breakfast")->dropdownList(GlobalConstant::YES_NO); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <?= $form->field($roomDetail, "[$row]checkIn")->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('arrival' . $row, 'arrival' . $row, true, false)); ?>
            </div>
            <div class="col-md">
                <?= $form->field($roomDetail, "[$row]checkOut")->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('departure' . $row, 'departure' . $row, true, false)); ?>
            </div>
        </div>
    </div>
</div>