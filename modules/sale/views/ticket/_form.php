<?php

use app\components\GlobalConstant;
use app\components\Helper;
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
    "var ticket = '" . Yii::$app->request->baseUrl . '/sale/ticket/add-ticket' . "';var _csrf='" . Yii::$app->request->getCsrfToken() . "'; var airlineUrl='" . Yii::$app->request->baseUrl . '/sale/airline/get-airline-details' . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/ticket.js',
    ['depends' => [JqueryAsset::className()]]
);
?>

<div class="ticket-form">
    <?php $form = ActiveForm::begin(['class' => 'form']); ?>
    <?php if ($model->isNewRecord) : ?>
        <div class="card card-custom card-sticky mb-5" id="kt_page_sticky_card">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">
                        Create Ticket <i class="mr-2"></i><small class="">try to scroll the page</small>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="#" id="addButton" class="btn btn-light-success font-weight-bolder mr-2"
                       onclick="addTicket()"
                       data-row-number="1">
                        <i class="ki ki-plus icon-sm"></i> Add More
                    </a>
                    <?= Html::submitButton(Yii::t('app', '<i class="ki ki-double-arrow-down icon-sm"></i>Save'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row pb-10">
                    <div class="col-md mb-5">
                        <?= Select2::widget(Helper::ajaxDropDown('customerId', '/sale/customer/get-customers', true)); ?>
                    </div>
                </div>
                <div class="row">
                    <?php
                    if ($model->isNewRecord) {
                        ?>
                        <div class="col-md">

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="customCheckbox1" name="invoice">
                                    <label for="customCheckbox1" class="custom-control-label">Create Inbox</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio1" name="invoice" value="1" checked="checked">
                                    <label for="customRadio1" class="custom-control-label">Group Invoice</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio2" name="invoice"  value="2">
                                    <label for="customRadio2" class="custom-control-label">Individual Ticket Invoice</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="customRequest">
                                    <label class="custom-control-label" for="customSwitch1">Create Custom Request</label>
                                </div>
                            </div>
                        </div>
                        <?php
                    } elseif (!$model->isNewRecord && !$model->invoice) {
                        ?>
                        <div class="col-md">
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
        <?= $this->render('ticket', ['row' => 0, 'model' => $model, 'ticketSupplier' => $model->ticketSupplier ?? new TicketSupplier(), 'form' => $form]); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>