<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\sale\components\ServiceConstant;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\JqueryAsset;
use yii\web\View;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\FlightProposal $model */
/** @var yii\bootstrap4\ActiveForm $form */

$this->registerJs(
    "var itinerary = '" . Yii::$app->request->baseUrl . '/sale/flight-proposal/add-itinerary' . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/proposal.js',
    ['depends' => [JqueryAsset::class]]
);

?>

<div class="flight-proposal-form">
    <?php $form = ActiveForm::begin(['class' => 'form']); ?>
    <div class="row g-5">
        <div class="col-md-6 col-lg-6 5order-md-first">
            <div class="card card-custom mb-5 sticky-top">
                <div class="card-header bg-gradient-green">
                    <div class="card-title">
                        <h5 class="card-label">
                            Create Flight Proposal
                        </h5>
                    </div>
                    <div class="card-toolbar float-right">
                        <a href="#" id="addButton" class="btn btn-success font-weight-bolder mr-2"
                           onclick="addItinerary()"
                           data-row-number="1">
                            <i class="fa fa-plus-circle"></i> Add More
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'airlineId')->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('airlineId', '/sale/airline/get-airlines', true, 'airlineId', 'airline', ($model->isNewRecord) ? [] : [$model->airlineId => $model->airline->name . ' | ' . $model->airline->code]))->label('Airline') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'class')->dropdownList(ServiceConstant::SEAT_CLASS,['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'departure')->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('departure', 'departure', true, false)); ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'arrival')->widget(DateTimePicker::class, WidgetHelper::getDateTimeWidget('arrival', 'arrival', true, false)); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'tripType')->dropdownList(GlobalConstant::TRIP_TYPE) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'numberOfAdult')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'pricePerAdult')->textInput(['type' => 'number']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'numberOfChild')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'pricePerChild')->textInput(['type' => 'number']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'numberOfInfant')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'pricePerInfant')->textInput(['type' => 'number']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'baggagePerAdult')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'baggagePerChild')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'baggagePerInfant')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'totalPrice')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'discount')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'notes')->textInput(['rows' => 6]) ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="itinerary">
                <?php
                if (!$model->isNewRecord) {
                    foreach ($model->flightProposalItineraries as $key => $itinerary) {
                        echo $this->render('itinerary', ['row' => $key, 'model' => $model, 'itinerary' => $itinerary, 'form' => $form]);
                    }
                } else {
                    echo $this->render('itinerary', ['row' => 0, 'model' => $model, 'itinerary' => $itinerary, 'form' => $form]);
                }
                ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
