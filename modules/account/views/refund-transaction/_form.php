<?php

use app\components\GlobalConstant;
use app\components\Helper;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\RefundTransaction $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJs(
    "var ajaxUrl = '" . Yii::$app->request->baseUrl . '/account/refund-transaction/pending-for-customer' . "'; var _csrf='" . Yii::$app->request->getCsrfToken() . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/refund_transaction.js',
    ['depends' => [JqueryAsset::className()]]
);

?>

<div class="refund-transaction-form">
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label" for="supplier">Select Customer</label>
                    <?= $form->field($model, 'refId')->widget(Select2::classname(), Helper::ajaxDropDown('customerId', '/sale/customer/get-customers', true, 'customerId', 'customer'))->label('Invoice To'); ?>
                </div>
                <div class="col-md-6">
                    <label class="control-label" for="dateRange">Issue Date Range</label>
                    <?= $form->field($model, 'dateRange', [
                        'options' => ['class' => 'drp-container mb-2']
                    ])->widget(DateRangePicker::class, Helper::getDateRangeWidgetOptions())->label(false) ?>
                </div>
            </div>
            <hr class="m-5">
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-9">
                    <div id="bills" style="border: 1px solid #ddd; background-color: #FFFFFF; padding: 10px;">
                        <h4>Pending Refunds</h4>
                        <hr>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px"><input type="checkbox" id="all-service"></th>
                                <th>Identification Number</th>
                                <th>Service</th>
                                <th>Issue Date</th>
                                <th>Payable To Customer</th>
                                <th>Receivable From Customer</th>
                            </tr>
                            </thead>
                            <tbody id="t-body">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-3" style="padding-left: 5px">
                    <div style="border: 1px solid #ddd; background-color: #FFFFFF; padding: 10px;">
                        <h4>Customer Payment details</h4>
                        <hr>

                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td><h4>Currency:</h4></td>
                                <td><h3>BDT</h3></td>
                            </tr>
                            <tr>
                                <td><h4>Total Due:</h4></td>
                                <td><h3 id="totalDue"></h3></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>


            <div class="row" style="margin-bottom: 10px;">
                <div class="col-9">
                    <div id="payment" style="border: 1px solid #ddd; background-color: #FFFFFF; padding: 10px;">
                        <h4>Payment Details for Customer</h4>
                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'paymentMode')->dropDownList(GlobalConstant::PAYMENT_MODE, ['prompt' => ''])->label('Payment Mode') ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'transactionDate')->widget(DateRangePicker::className(), Helper::dateFormat())->label('Transaction Date') ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'bankId')->widget(Select2::class, Helper::ajaxDropDown('bankId', '/account/bank-account/get-banks', true, 'bankId', 'bank'))->label('Bank'); ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'amount')->textInput(['value' => 0, 'type' => 'number', 'step' => 'any']) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'adjustAmount')->textInput(['value' => 0, 'type' => 'number']) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'payableAmount5')->textInput(['value' => 0, 'type' => 'number', 'step' => 'any']) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'receivableAmount')->textInput(['value' => 0, 'type' => 'number', 'step' => 'any']) ?>
                            </div>
                            <div class="col-md-8">
                                <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>
                            </div>
                        </div>
                        <div class="form-group float-right">
                            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <? /*= $form->field($model, 'uid')->textInput(['maxlength' => true]) */ ?><!--

    <? /*= $form->field($model, 'refId')->textInput() */ ?>

    <? /*= $form->field($model, 'refModel')->textInput(['maxlength' => true]) */ ?>

    <? /*= $form->field($model, 'payableAmount')->textInput() */ ?>

    <? /*= $form->field($model, 'receivableAmount')->textInput() */ ?>

    <? /*= $form->field($model, 'totalAmount')->textInput() */ ?>

    <? /*= $form->field($model, 'paymentStatus')->dropDownList([ 'Payable' => 'Payable', 'Receivable' => 'Receivable', ], ['prompt' => '']) */ ?>

    <? /*= $form->field($model, 'adjustedAmount')->textInput() */ ?>

    <? /*= $form->field($model, 'isAdjusted')->textInput() */ ?>

    <? /*= $form->field($model, 'remarks')->textarea(['rows' => 6]) */ ?>

    <? /*= $form->field($model, 'status')->textInput() */ ?>

    <? /*= $form->field($model, 'createdBy')->textInput() */ ?>

    <? /*= $form->field($model, 'createdAt')->textInput() */ ?>

    <? /*= $form->field($model, 'updatedBy')->textInput() */ ?>

    --><? /*= $form->field($model, 'updatedAt')->textInput() */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
