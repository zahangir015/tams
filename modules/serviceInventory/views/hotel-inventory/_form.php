<?php

use app\components\WidgetHelper;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\web\JqueryAsset;
use yii\web\View;
use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\HotelInventory $model */
/** @var app\modules\serviceInventory\models\HotelInventoryRoomDetail $model */
/** @var app\modules\serviceInventory\models\HotelInventoryAmenity $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJs(
    "var room = '".Yii::$app->request->baseUrl.'/serviceInventory/hotel-inventory/add-room'."';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/proposal.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="hotel-inventory-form">
    <?php $form = ActiveForm::begin(['class' => 'form']); ?>
    <div class="row g-5">
        <div class="col-md-6 col-lg-6 order-md-first">
            <div class="card card-custom mb-5 sticky-top">
                <div class="card-header bg-gradient-green">
                    <div class="card-title">
                        <?= Html::encode($this->title) ?>
                    </div>
                    <div class="card-toolbar float-right">
                        <a href="#" id="addButton" class="btn btn-success font-weight-bolder mr-2"
                           onclick="addRoom()" data-row-number="1">
                           <i class="fa fa-plus-circle"></i> Add More
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'hotelCategoryId')->dropDownList($categories) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'supplierId')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'hotelName')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'hotelAddress')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, "countryId")->widget(Select2::class, WidgetHelper::ajaxSelect2Widget('countryId', '/country/get-countries', true, 'countryId', 'country', ($model->country) ? [$model->countryId => $model->country->name] : []))->label('Country') ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, "cityId")->widget(DepDrop::class, WidgetHelper::depDropConfigurationGenerate($model, 'cityId', 'countryId', '/city/get-city-by-country', ($model->city) ? [$model->cityId => $model->city->name] : [])) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model2, 'roomTypeId')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model2, 'meal')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model2, 'extraBed')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model2, 'numberOfRoom')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model2, 'isAvailable')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model2, 'cancelationPolicy')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model2, 'perNightCost')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model2, 'currency')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model2, 'perNightSelling')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model2, 'priceValidity')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model2, 'transfer')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model2, 'transferDetails')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model3, 'hotelInventoryRoomDetailId')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model3, 'amenityId')->textInput() ?>
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
                    if(!$model->isNewRecord)
                    {
                        foreach($model->roomDetails as $key=>$roomDetail)
                        {
                            echo $this->render('room',['row' => $key, 'model' => $model, 'roomDetail' => $roomDetail, 'roomTypes' => $roomTypes, 'form' => $form]);
                        }
                    }
                    else
                    {
                        echo $this->render('room', ['row' => 0, 'model' => $model, 'roomDetail' => $roomDetail, 'roomTypes' => $roomTypes, 'form' => $form]);
                    }
                ?>
            </div>
            <div class="itinerary">
                <h3>Amenities</h3>
                <input type="checkbox" name="" id=""> Wifi<span>&nbsp;&nbsp;</span>
                <input type="checkbox" name="" id=""> Toilet Paper<span>&nbsp;&nbsp;</span>
                <input type="checkbox" name="" id=""> Smoking Zone<span>&nbsp;&nbsp;</span>
                <input type="checkbox" name="" id=""> Gym<span>&nbsp;&nbsp;</span>
                <input type="checkbox" name="" id=""> Dryer<span>&nbsp;&nbsp;</span>
                <input type="checkbox" name="" id=""> Terrible Dryer
            </div>
        </div>
    </div>
</div>
