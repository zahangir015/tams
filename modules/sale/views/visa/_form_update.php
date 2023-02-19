<?php

use app\modules\sale\components\ServiceConstant;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use app\components\Utilities;
use yii\web\JqueryAsset;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\Visa */
/* @var $form yii\bootstrap4\ActiveForm */

$this->title = Yii::t('app', 'Update Visa');
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
                        <?= $form->field($model, 'customerId')->widget(Select2::class, Utilities::ajaxDropDown('customerId', '/sale/customer/get-customers', true, 'customerId', 'customer', [$model->customer->id => $model->customer->company]))->label('Customer') ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'issueDate')->widget(DateRangePicker::class, Utilities::dateFormat(false, true)) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, 'identificationNumber')->textInput(['value' => ($model->isNewRecord) ? Utilities::visaIdentificationNumber() : $model->identificationNumber, 'readOnly' => 'readOnly']) ?>
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
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-lg-8">
                <div class="card-holder">
                    <?php
                    foreach ($model->visaSuppliers as $key => $visaSupplier) {
                        ?>
                        <?= $this->render('supplier', ['row' => $key, 'model' => $model, 'visaSupplier' => $visaSupplier, 'form' => $form]); ?>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
