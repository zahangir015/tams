<?php

use app\components\WidgetHelper;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\bootstrap4\ActiveForm;
use app\components\Utilities;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\holiday\Holiday */
/* @var $form yii\bootstrap4\ActiveForm */


$this->registerJs(
    "var supplier = '" . Yii::$app->request->baseUrl . '/sale/holiday/add-supplier' . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/holiday.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="holiday-form">

    <?php $form = ActiveForm::begin(['class' => 'form']); ?>

    <?php if ($model->isNewRecord) : ?>
        <div class="card card-custom mb-5 sticky-top">
            <div class="card-header">
                <div class="card-title">
                    <h5 class="card-label">
                        Create Holiday
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
                        <?= $form->field($model, 'customerId')->widget(Select2::class, Utilities::ajaxDropDown('customerId', '/sale/customer/get-customers', true, 'customerId'))->label('Customer') ?>
                        <small id="passwordHelpBlock" class="form-text text-muted">
                            Add Customer if not available. <?=  Html::a('Create Customer', '/sale/customer/create', ['target' => '_blank'])?>
                        </small>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'issueDate')->widget(DatePicker::class, WidgetHelper::getDateWidget('issueDate', 'issueDate', false, true)) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'holidayCategoryId')->dropdownList($holidayCategories, ['prompt' => ''])->label('Category') ?>
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
                                <?= $form->field($model, 'identificationNumber')->textInput(['value' => ($model->isNewRecord) ? Utilities::holidayIdentificationNumber() : $model->identificationNumber, 'readOnly' => 'readOnly']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
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
                    <?= $this->render('supplier', ['row' => 0, 'model' => $model, 'holidaySupplier' => $model->holidaySupplier ?? $holidaySupplier, 'form' => $form, 'holidayCategories' => $holidayCategories]); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>
</div>
