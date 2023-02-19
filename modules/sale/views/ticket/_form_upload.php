<?php

use app\components\Utilities;
use app\components\Utils;
use app\modules\sales\models\Provider;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\sales\models\ticket\Ticket */
/* @var $form yii\bootstrap4\ActiveForm */
$this->title = Yii::t('app', 'Upload Ticket');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-create">

    <div class="ticket-form">
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'customerId')->widget(Select2::class, Utilities::ajaxDropDown('customerId', '/sale/customer/get-customers', true, 'customerId'))->label('Customer') ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($ticketSupplier, "supplierId")->widget(Select2::class, Utilities::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', true, 'supplierId', 'supplierId'))->label('Supplier'); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, "airlineId")->widget(Select2::class, Utilities::ajaxDropDown('airlineId', '/sale/airline/get-airlines', true, 'airlineId', 'airlineId'))->label('Airline'); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'providerId')->widget(Select2::class, Utilities::ajaxDropDown('providerId', '/sale/provider/get-providers', true,'providerId'))->label('Provider') ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'csv')->fileInput(['class' => 'form-control'])->label('Upload Ticket Data') ?>
                        <p class="help-block">Upload CSV file with ticket data. Download a sample csv <a href="download-sample" target="_blank">sample.csv</a></p>
                    </div>
                    <div class=" col-md-4" style=" margin-top: 15px;">
                        <?= Html::submitButton(Yii::t('app', 'Upload ticket'), ['class' => 'btn btn-primary', 'id' => 'addTicket']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
