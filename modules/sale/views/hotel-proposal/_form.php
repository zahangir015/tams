<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\HotelProposal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="hotel-proposal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agencyId')->textInput() ?>

    <?= $form->field($model, 'hotelCategoryId')->textInput() ?>

    <?= $form->field($model, 'hotelName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hotelAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'countryId')->textInput() ?>

    <?= $form->field($model, 'cityId')->textInput() ?>

    <?= $form->field($model, 'numberOfAdult')->textInput() ?>

    <?= $form->field($model, 'numberOfChild')->textInput() ?>

    <?= $form->field($model, 'amenities')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'totalPrice')->textInput() ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'createdBy')->textInput() ?>

    <?= $form->field($model, 'createdAt')->textInput() ?>

    <?= $form->field($model, 'updatedBy')->textInput() ?>

    <?= $form->field($model, 'updatedAt')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
