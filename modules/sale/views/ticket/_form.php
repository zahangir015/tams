<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\sale\models\Provider;
use app\modules\sale\models\Supplier;
use app\modules\sale\models\ticket\TicketSupplier;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model \app\modules\sale\models\ticket\Ticket */
/* @var $form yii\bootstrap4\ActiveForm */

$this->registerJs(
    "var ticket = '" . Yii::$app->request->baseUrl . '/sale/ticket/add-ticket' . "';var _csrf='" . Yii::$app->request->getCsrfToken() . "'; 
        var airlineUrl='" . Yii::$app->request->baseUrl . '/sale/airline/get-airline-details' . "'; var parentTicketUrl='" . Yii::$app->request->baseUrl . '/sale/ticket/get-parent-ticket-details' . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/flight.js',
    ['depends' => [JqueryAsset::className()]]
);
?>

<div class="ticket-form">
    <?php $form = ActiveForm::begin(['class' => 'form']); ?>
    <?php if ($model->isNewRecord) : ?>
        <div class="card card-custom mb-5 sticky-top">
            <div class="card-header">
                <div class="card-title">
                    <h5 class="card-label">
                        Create Ticket
                    </h5>
                </div>
                <div class="card-toolbar float-right">
                    <a href="#" id="addButton" class="btn btn-success font-weight-bolder mr-2"
                       onclick="addTicket()"
                       data-row-number="1">
                        <i class="fa fa-plus-circle"></i> Add More
                        <span class="badge bg-yellow" id="count"></span>
                    </a>
                    <?= Html::submitButton(Yii::t('app', '<i class="fa fa-arrow-alt-circle-down"></i> Save'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row pb-10">
                    <div class="col-md">
                        Select Customer
                        <?= Select2::widget(WidgetHelper::ajaxSelect2Widget('customerId', '/sale/customer/get-customers', true)); ?>
                        <small id="passwordHelpBlock" class="form-text text-muted">
                            Add Customer if not available. <?=  Html::a('Create Customer', '/sale/customer/create', ['target' => '_blank'])?>
                        </small>
                    </div>
                    <?php
                    if ($model->isNewRecord) {
                        ?>
                        <div class="col-md mt-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="customCheckbox1"
                                           name="invoice">
                                    <label for="customCheckbox1" class="custom-control-label">Create Invoice</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md mt-4">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio1" name="group"
                                           value="1" checked="checked">
                                    <label for="customRadio1" class="custom-control-label">Group Invoice</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md mt-4">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio2" name="group"
                                           value="2">
                                    <label for="customRadio2" class="custom-control-label">Individual Ticket
                                        Invoice</label>
                                </div>
                            </div>
                        </div>
                        <?php
                    } elseif (!$model->isNewRecord && !$model->invoice) {
                        ?>
                        <div class="col-md mt-5">
                            <div class="form-group row">
                                <div class="col-2">
                           <span class="switch switch-icon">
                            <label>
                             <input type="checkbox" name="invoice"/>
                             <span></span>
                            </label>
                           </span>
                                </div>
                                <label class="col-10 col-form-label font-weight-bolder">Want to create Invoice?</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group row">
                                <div class="col-2">
                           <span class="switch switch-icon">
                            <label>
                             <input type="checkbox" name="individual"/>
                             <span></span>
                            </label>
                           </span>
                                </div>
                                <label class="col-10 col-form-label font-weight-bolder">Want to create individual
                                    invoice
                                    per
                                    ticket.</label>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="card-holder">
        <?= $this->render('ticket', ['row' => 0, 'model' => $model, 'ticketSupplier' => $model->ticketSupplier ?? new TicketSupplier(), 'supplierDataArray' => $supplierDataArray, 'providerDataArray' => Provider::query(), 'form' => $form]); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>