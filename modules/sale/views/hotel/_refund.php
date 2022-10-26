<?php

use app\components\GlobalConstant;
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
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotels'), 'url' => ['refund-list']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(
    '@web/js/hotel.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="hotel-form">
    <?php $form = ActiveForm::begin(['class' => 'form']); ?>

    <?php if ($model->isNewRecord) : ?>
        <div class="card card-custom mb-5 sticky-top">
            <div class="card-header">
                <div class="card-title">
                    <h5 class="card-label">
                        Create Hotel
                    </h5>
                </div>
                <div class="card-toolbar float-right">
                    <a href="#" id="addButton" class="btn btn-success font-weight-bolder mr-2"
                       onclick="addSupplier()"
                       data-row-number="1">
                        <i class="fa fa-plus-circle"></i> Add More
                    </a>
                    <?= Html::submitButton(Yii::t('app', '<i class="fa fa-arrow-alt-circle-down"></i> Save'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <?= $form->field($model, 'customerId')->dropdownList([$motherHoliday->customer->id => $motherHoliday->customer->company], ['readOnly' => 'readOnly'])->label('Customer') ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'holidayCategoryId')->dropdownList($holidayCategories, ['prompt' => '', 'readOnly' => 'readOnly', 'value' => $motherHoliday->holidayCategoryId])->label('Category') ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'issueDate')->textInput(['value' => $motherHoliday->issueDate, 'readOnly' => 'readOnly']) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'refundRequestDate')->widget(DateRangePicker::class, Helper::dateFormat(false, true)) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'voucherNumber')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'reservationCode')->textInput(['maxlength' => true]) ?>
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
                                <?= $form->field($model, 'identificationNumber')->textInput(['value' => ($model->isNewRecord) ? Helper::hotelIdentificationNumber() : $model->identificationNumber, 'readOnly' => 'readOnly']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'costOfSale')->textInput(['type' => 'number', 'value' => 0, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Cost Of Sale') ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'quoteAmount')->textInput(['type' => 'number', 'value' => 0, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Quote Amount') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'netProfit')->textInput(['type' => 'number', 'value' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Net Profit') ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'totalNights')->textInput(['type' => 'number', 'value' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Nights') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'checkInDate')->widget(DateRangePicker::class, Helper::dateFormat(false, true)) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'checkOutDate')->widget(DateRangePicker::class, Helper::dateFormat(false, true)) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'freeCancellationDate')->widget(DateRangePicker::class, Helper::dateFormat(false, true)) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'isRefundable')->dropDownList(GlobalConstant::YES_NO) ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if ($model->isNewRecord) {
                                ?>
                                <div class="col-md">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio1"
                                                   name="invoice" value="1">
                                            <label for="customRadio1" class="custom-control-label">Create
                                                Invoice</label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-lg-8">
                <div class="card-holder">
                    <?= $this->render('supplier', ['row' => 0, 'model' => $model, 'hotelSupplier' => $model->hotelSupplier ?? $hotelSupplier, 'form' => $form]); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>
</div>