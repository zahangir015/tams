<?php

use app\components\GlobalConstant;
use app\modules\sale\components\ServiceConstant;
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

$this->title = Yii::t('app', 'Refund Visa');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Visa'), 'url' => ['refund-list']];
$this->params['breadcrumbs'][] = $this->title;

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
        <div class="card card-custom mb-5 sticky-top">
            <div class="card-header">
                <div class="card-title">
                    <h5 class="card-label">
                        Refund Visa
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <?= $form->field($model, 'refundRequestDate')->widget(DateRangePicker::class, Helper::dateFormat(false, true)) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'customerId')->dropdownList([$motherVisa->customer->id => $motherVisa->customer->company], ['readOnly' => 'readOnly'])->label('Customer') ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'issueDate')->textInput(['value' => $motherVisa->issueDate, 'readOnly' => 'readOnly']) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'processStatus')->dropdownList(ServiceConstant::VISA_PROCESS_STATUS) ?>
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
                                <?= $form->field($model, 'identificationNumber')->textInput(['value' => ($model->isNewRecord) ? Helper::visaIdentificationNumber() : $model->identificationNumber, 'readOnly' => 'readOnly']) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'totalQuantity')->textInput(['type' => 'number', 'value' => $model->totalQuantity, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly']) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'quoteAmount')->textInput(['type' => 'number', 'value' => $model->quoteAmount, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Quote Amount') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($model, 'costOfSale')->textInput(['type' => 'number', 'value' => $model->costOfSale, 'min' => 0, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Total Cost Of Sale') ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($model, 'netProfit')->textInput(['type' => 'number', 'value' => $model->netProfit, 'step' => 'any', 'readOnly' => 'readOnly'])->label('Net Profit') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($visaRefund, 'isRefunded')->dropDownList(GlobalConstant::YES_NO) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($visaRefund, 'refundStatus')->dropDownList(ServiceConstant::OTHER_SERVICE_REFUND_STATUS, ['prompt' => 'Select refund status...']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <?= $form->field($visaRefund, 'refundMedium')->dropdownList(ServiceConstant::REFUND_MEDIUM, ['prompt' => 'Select refund medium...']) ?>
                            </div>
                            <div class="col-md">
                                <?= $form->field($visaRefund, 'refundMethod')->dropdownList(ServiceConstant::REFUND_METHOD, ['prompt' => 'Select refund method...']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-lg-8">
                <div class="card-holder">
                    <?php
                    foreach ($model->visaSuppliers as $key => $visaSupplier) {
                        ?>
                        <?= $this->render('refund_supplier', ['row' => $key, 'model' => $model, 'visaSupplier' => $visaSupplier, 'form' => $form]); ?>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
