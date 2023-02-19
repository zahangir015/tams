<?php

use app\components\Utilities;
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
    "var ajaxUrl = '" . Yii::$app->request->baseUrl . '/account/invoice/pending' . "';",
    \yii\web\View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/invoice.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="invoice-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'customerId')->widget(Select2::classname(), Utilities::ajaxDropDown('customerId', '/sale/customer/get-customers', true, 'customerId', 'customer'))->label('Invoice To'); ?>
                        </div>
                        <div class="col-md">
                            <label class="control-label" for="dateRange">Issue Date</label>
                            <?= $form->field($model, 'dateRange', [
                                'options' => ['class' => 'drp-container mb-2']
                            ])->widget(DateRangePicker::class, Utilities::getDateRangeWidgetOptions())->label(false) ?>
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
                                    <th>Identification Number</th>
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
                                    <th colspan="2" class="text-end fs-4 text-nowrap">BDT <span
                                                id="grand-total">0.00</span></th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="separator separator-dashed my-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom" id="kt_page_sticky_card">
                <div class="card-body">
                    <h4>Payment details</h4>
                    <table class="table g-5 gs-0 mb-0 fw-bolder text-gray-700 mb-10">
                        <tbody>
                        <tr>
                            <td>Currency:</td>
                            <td>BDT</td>
                        </tr>
                        <tr>
                            <td>Total Due:</td>
                            <td id="totalPayable"></td>
                            <?= $form->field($model, 'dueAmount')->hiddenInput(['value' => 0, 'id' => 'invoiceAmount'])->label(false) ?>
                        </tr>
                        <tr>
                            <td>Total Selected:</td>
                            <td id="total"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'date')->widget(DatePicker::class, Utilities::getDatewidget('date', 'date', false)) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'expectedPaymentDate')->widget(DatePicker::class, Utilities::getDatewidget('expectedPaymentDate', 'expectedPaymentDate', false)) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?php echo $form->field($model, 'invoiceFile')->widget(FileInput::classname(), Utilities::fileInputWidget())->label('Upload File'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md" id="comment">
                            <?= $form->field($model, 'remarks')->textarea() ?>
                        </div>
                    </div>

                    <div class="row mt-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
