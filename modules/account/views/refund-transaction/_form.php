<?php

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\sale\models\Customer;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\RefundTransaction $model */
/** @var yii\bootstrap4\ActiveForm $form */

$this->registerJs(
    "var ajaxUrl = '" . Yii::$app->request->baseUrl . '/account/refund-transaction/customer-pending' . "'; var _csrf='" . Yii::$app->request->getCsrfToken() . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/refund_transaction.js',
    ['depends' => [JqueryAsset::className()]]
);

?>

<div class="refund-transaction-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'refId')->widget(Select2::classname(), Helper::ajaxDropDown('refId', '/sale/customer/get-customers', true, 'customerId', 'customer'))->label('Refund To'); ?>
                            <?= $form->field($model, 'refModel')->hiddenInput(['value' => Customer::class])->label(false) ?>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="dateRange">Issue Date Range</label>
                            <?= $form->field($model, 'dateRange', [
                                'options' => ['class' => 'drp-container mb-2']
                            ])->widget(DateRangePicker::class, Helper::getDateRangeWidgetOptions())->label(false) ?>
                        </div>
                    </div>
                    <div id="bills" style="border: 1px solid #ddd; background-color: #FFFFFF; padding: 10px;">
                        <h4>Pending Refunds</h4>
                        <hr>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px"><input type="checkbox" id="all-service"></th>
                                <th>Identification#</th>
                                <th>Service</th>
                                <th>Issue</th>
                                <th>Payable</th>
                                <th>Receivable</th>
                            </tr>
                            </thead>
                            <tbody id="t-body">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div id="payment" style="border: 1px solid #ddd; background-color: #FFFFFF; padding: 10px;">
                        <h4>Payment Details for Customer</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?= $form->field($transaction, 'bankId')->widget(Select2::class, Helper::ajaxDropDown('bankId', '/account/bank-account/get-banks', true, 'bankId', 'bank'))->label('Bank'); ?>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?= $form->field($transaction, 'reference')->textInput() ?>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?= $form->field($transaction, 'paymentMode')->dropDownList(GlobalConstant::PAYMENT_MODE, ['prompt' => ''])->label('Payment Mode') ?>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?= $form->field($transaction, 'paymentDate')->widget(DateRangePicker::className(), Helper::dateFormat()) ?>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?= $form->field($model, 'payableAmount')->textInput(['value' => 0, 'type' => 'number', 'step' => 'any', 'readOnly' => true]) ?>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?= $form->field($model, 'receivableAmount')->textInput(['value' => 0, 'type' => 'number', 'step' => 'any', 'readOnly' => true]) ?>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?= $form->field($model, 'totalAmount')->textInput(['value' => 0, 'type' => 'number', 'step' => 'any', 'readOnly' => true]) ?>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?= $form->field($model, 'adjustedAmount')->textInput(['value' => 0, 'type' => 'number', 'step' => 'any']) ?>
                            </div>
                            <div class="col-md-12">
                                <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>
                            </div>
                        </div>
                        <div class="form-group float-right">
                            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

