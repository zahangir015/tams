<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Invoice */
/* @var $form yii\bootstrap4\ActiveForm */

$this->registerJs(
    "var ajaxUrl = '" . Yii::$app->request->baseUrl . '/account/bill/pending' . "';",
    \yii\web\View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/bill.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="bill-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'supplierId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId', 'supplier'))->label('Bill To'); ?>
                        </div>
                        <div class="col-md">
                            <label class="control-label" for="dateRange">Issue Date</label>
                            <?= $form->field($model, 'dateRange', [
                                'options' => ['class' => 'drp-container mb-2']
                            ])->widget(DateRangePicker::class, WidgetHelper::getDateRangeWidget())->label(false) ?>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700 mb-10">
                                <thead>
                                <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                                    <th style="width: 10px"><input type="checkbox" id="all"></th>
                                    <th>Service</th>
                                    <th>Identification#</th>
                                    <th>Issue Date</th>
                                    <th>Amount Due</th>
                                </tr>
                                </thead>
                                <tbody id="t-body">

                                </tbody>
                                <tfoot>
                                    <tr class="align-top fw-bolder text-gray-700">
                                        <th></th>
                                        <th colspan="3" class="fs-4 ps-0">Total</th>
                                        <th colspan="2" class="text-end fs-4 text-nowrap">BDT <span id="grand-total">0.00</span></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="separator separator-dashed my-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-custom" id="kt_page_sticky_card">
                <div class="card-body">
                    <h4>Payment details</h4>
                    <hr>
                    <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700 mb-10">
                        <tbody>
                        <tr>
                            <td>Currency:</td>
                            <td>BDT</td>
                        </tr>
                        <tr>
                            <td>Total Due:</td>
                            <td id="totalPayable"></td>
                        </tr>
                        <tr>
                            <td>Total Selected:</td>
                            <td id="total"></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'billNumber')->textInput(['maxlength' => true, 'id' => 'billNumber', 'readOnly' => true, 'value' => ($model->isNewRecord) ? Utilities::billNumber() : $model->billNumber])->label('Bill Number') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'dueAmount')->textInput(['maxlength' => true, 'id' => 'dueAmount'])->label('Due') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($transaction, 'bankId')->widget(Select2::class, [
                                'theme' => Select2::THEME_DEFAULT,
                                'data' => $bankList,
                                'options' => [
                                    'id' => 'bankId',
                                    'class' => 'form-control',
                                    'placeholder' => 'Select a bank ...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Bank');
                            ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($transaction, 'reference')->textInput(['maxlength' => true])->label('Reference') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($transaction, 'paidAmount')->textInput(['value' => $model->dueAmount, 'max' => $model->dueAmount, 'min' => 1])->label('Paying Amount') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'discountedAmount')->textInput(['value' => $model->dueAmount, 'max' => $model->dueAmount, 'min' => 1]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($transaction, 'refundAdjustmentAmount')->textInput(['value' => 0]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($transaction, 'paymentCharge')->textInput(['value' => 0]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($transaction, 'paymentMode')->dropdownList(GlobalConstant::PAYMENT_MODE, ['prompt' => '']); ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($transaction, 'paymentDate')->widget(DatePicker::class, WidgetHelper::getDatewidget('paymentDate', 'paymentDate', false, true)); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div id="files" style="background-color: #FFFFFF; padding: 10px;">
                                <?= $form->field($model, 'billFile[]')->widget(FileInput::class, [
                                    'options' => [
                                        'multiple' => true,
                                        'accept' => '*'
                                    ],
                                    'pluginOptions' => [
                                        'maxFileCount' => 10,
                                    ]
                                ])->label('Upload files');
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Pay'), ['class' => $model->isNewRecord ? 'btn btn-success float-right' : 'btn btn-primary float-right']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
