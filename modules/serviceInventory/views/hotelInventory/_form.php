<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\HotelInventory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="hotel-inventory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supplierId')->textInput() ?>

    <?= $form->field($model, 'hotelName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hotelAddress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'countryId')->textInput() ?>

    <?= $form->field($model, 'cityId')->textInput() ?>

    <?= $form->field($model, 'hotelCategoryId')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'createdAt')->textInput() ?>

    <?= $form->field($model, 'createdBy')->textInput() ?>

    <?= $form->field($model, 'updatedAt')->textInput() ?>

    <?= $form->field($model, 'updatedBy')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
