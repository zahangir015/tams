<?php

use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use app\components\Helper;
use yii\web\JqueryAsset;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\Visa */
/* @var $form yii\bootstrap4\ActiveForm */

$this->registerJs(
    "var supplier = '" . Yii::$app->request->baseUrl . '/sale/visa/add-supplier' . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/visa.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="visa-form">

    <?php $form = ActiveForm::begin(['class' => 'form']); ?>

    <?php if ($model->isNewRecord) : ?>
        <div class="card card-custom mb-5 sticky-top">
            <div class="card-header">
                <div class="card-title">
                    <h5 class="card-label">
                        Create Visa
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
                        <?= $form->field($model, 'customerId')->widget(Select2::class, Helper::ajaxDropDown('customerId', '/sale/customer/get-customers', true, 'customerId'))->label('Customer') ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'issueDate')->widget(DateRangePicker::class, Helper::dateFormat(false, true)) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'identificationNumber')->textInput(['value' => ($model->isNewRecord) ? Helper::visaIdentificationNumber() : $model->identificationNumber, 'readOnly' => 'readOnly']) ?>
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
                                <?= $form->field($model, 'processStatus')->textInput() ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'totalQuantity')->textInput() ?>
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
                                <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>
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
                    <?= $this->render('supplier', ['row' => 0, 'model' => $model, 'visaSupplier' => $model->visaSupplier ?? $visaSupplier, 'form' => $form]); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>
