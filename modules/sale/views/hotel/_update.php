<?php

use app\components\GlobalConstant;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\bootstrap4\ActiveForm;
use app\components\Utilities;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\hotel\Hotel */
/* @var $form yii\bootstrap4\ActiveForm */


$this->registerJs(
    "var supplier = '" . Yii::$app->request->baseUrl . '/sale/hotel/add-supplier' . "';",
    View::POS_HEAD,
    'url'
);

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
                        Update Hotel
                    </h5>
                </div>
                <div class="card-toolbar float-right">
                    <a href="#" id="addButton" class="btn btn-success font-weight-bolder mr-2"
                       onclick="addSupplier()"
                       data-row-number="1">
                        <i class="fa fa-plus-circle"></i> Add More
                    </a>
                    <?= Html::submitButton(Yii::t('app', '<i class="fa fa-arrow-alt-circle-down"></i> Update'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <?= $form->field($model, 'customerId')->widget(Select2::class, Utilities::ajaxDropDown('customerId', '/sale/customer/get-customers', true, 'customerId', 'customer', [$model->customer->id => $model->customer->company]))->label('Customer') ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'issueDate')->widget(DateRangePicker::class, Utilities::dateFormat(false, true)) ?>
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
                                <?= $form->field($model, 'identificationNumber')->textInput(['value' => ($model->isNewRecord) ? Utilities::hotelIdentificationNumber() : $model->identificationNumber, 'readOnly' => 'readOnly']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'costOfSale')->textInput(['type' => 'number', 'value' => $model->costOfSale, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Cost Of Sale') ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'quoteAmount')->textInput(['type' => 'number', 'value' => $model->quoteAmount, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Quote Amount') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'netProfit')->textInput(['type' => 'number', 'value' => $model->netProfit, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Net Profit') ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'totalNights')->textInput(['type' => 'number', 'value' => $model->totalNights, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Nights') ?>
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
                                <?= $form->field($model, 'checkInDate')->widget(DateRangePicker::class, Utilities::dateFormat(false, true)) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'checkOutDate')->widget(DateRangePicker::class, Utilities::dateFormat(false, true)) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'freeCancellationDate')->widget(DateRangePicker::class, Utilities::dateFormat(false, true)) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'isRefundable')->dropDownList(GlobalConstant::YES_NO) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-lg-8">
                <div class="card-holder">
                    <?php
                    foreach ($model->hotelSuppliers as $key => $hotelSupplier) {
                        ?>
                        <?= $this->render('supplier', ['row' => $key, 'model' => $model, 'hotelSupplier' => $hotelSupplier, 'form' => $form]); ?>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
