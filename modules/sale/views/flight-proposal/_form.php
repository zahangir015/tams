<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\FlightProposal $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="flight-proposal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agencyId')->textInput() ?>

    <?= $form->field($model, 'airlineId')->textInput() ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tripType')->textInput() ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'departure')->textInput() ?>

    <?= $form->field($model, 'arrival')->textInput() ?>

    <?= $form->field($model, 'numberOfAdult')->textInput() ?>

    <?= $form->field($model, 'pricePerAdult')->textInput() ?>

    <?= $form->field($model, 'baggagePerAdult')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numberOfChild')->textInput() ?>

    <?= $form->field($model, 'pricePerChild')->textInput() ?>

    <?= $form->field($model, 'baggagePerChild')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numberOfInfant')->textInput() ?>

    <?= $form->field($model, 'pricePerInfant')->textInput() ?>

    <?= $form->field($model, 'baggagePerInfant')->textInput(['maxlength' => true]) ?>

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
