<?php

use app\components\GlobalConstant;
use app\modules\sale\components\ServiceConstant;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\bootstrap4\ActiveForm;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\holiday\Holiday */
/* @var $form yii\bootstrap4\ActiveForm */


$this->title = Yii::t('app', 'Refund Holiday');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Holidays'), 'url' => ['refund-list']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(
    '@web/js/holiday.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="holiday-form">

    <?php $form = ActiveForm::begin(['class' => 'form']); ?>

    <div class="card card-custom mb-5 sticky-top">
        <div class="card-header">
            <div class="card-title">
                <h5 class="card-label">
                    Refund Holiday
                </h5>
            </div>
            <div class="card-toolbar float-right">
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
                            <?= $form->field($model, 'identificationNumber')->textInput(['value' => Helper::holidayIdentificationNumber(), 'readOnly' => 'readOnly']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'route')->textInput(['maxlength' => true, 'readOnly' => 'readOnly', 'value' => $motherHoliday->route]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'costOfSale')->textInput(['type' => 'number', 'value' => $motherHoliday->costOfSale, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Cost Of Sale') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'quoteAmount')->textInput(['type' => 'number', 'value' => $motherHoliday->quoteAmount, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Quote Amount') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'netProfit')->textInput(['type' => 'number', 'value' => $motherHoliday->netProfit, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Net Profit') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'type')->dropdownList(ServiceConstant::SERVICE_TYPE_FOR_CREATE, ['value' => ServiceConstant::SERVICE_TYPE_FOR_CREATE['Refund'], 'readOnly' => 'readOnly'])->label('Type') ?>
                            <?= $form->field($model, 'motherId')->hiddenInput(['value' => $motherHoliday->id])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-lg-8">
            <div class="card-holder">
                <?php
                foreach ($motherHoliday->holidaySuppliers as $key => $holidaySupplier) {
                    ?>
                    <?= $this->render('refund_supplier', ['row' => $key, 'model' => $model, 'holidaySupplier' => $holidaySupplier, 'form' => $form, 'holidayCategories' => $holidayCategories]); ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
