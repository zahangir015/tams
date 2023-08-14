<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\HotelProposal $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJs(
    "var room = '" . Yii::$app->request->baseUrl . '/sale/hotel-proposal/add-room' . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/proposal.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="hotel-proposal-form">
    <?php $form = ActiveForm::begin(['class' => 'form']); ?>
    <div class="row g-5">
        <div class="col-md-6 col-lg-6 5order-md-first">
            <div class="card card-custom mb-5 sticky-top">
                <div class="card-header bg-gradient-green">
                    <div class="card-title">
                        <?= Html::encode($this->title) ?>
                    </div>
                    <div class="card-toolbar float-right">
                        <a href="#" id="addButton" class="btn btn-success font-weight-bolder mr-2"
                           onclick="addRoom()"
                           data-row-number="1">
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
                            <?= $form->field($model, 'hotelName')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'hotelAddress')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'countryId')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'cityId')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'numberOfAdult')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'numberOfChild')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'totalPrice')->textInput() ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'discount')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($model, 'amenities')->textarea(['rows' => 6]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
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
                    foreach ($model->roomDetails as $key => $roomDetail) {
                        echo $this->render('room', ['row' => $key, 'model' => $model, 'roomDetail' => $roomDetail, 'roomTypes' =>  $roomTypes, 'form' => $form]);
                    }
                } else {
                    echo $this->render('room', ['row' => 0, 'model' => $model, 'roomDetail' => $roomDetail, 'roomTypes' =>  $roomTypes, 'form' => $form]);
                }
                ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
