<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\search\FlightProposalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="flight-proposal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'agencyId') ?>

    <?= $form->field($model, 'airlineId') ?>

    <?= $form->field($model, 'class') ?>

    <?php // echo $form->field($model, 'tripType') ?>

    <?php // echo $form->field($model, 'route') ?>

    <?php // echo $form->field($model, 'departure') ?>

    <?php // echo $form->field($model, 'arrival') ?>

    <?php // echo $form->field($model, 'numberOfAdult') ?>

    <?php // echo $form->field($model, 'pricePerAdult') ?>

    <?php // echo $form->field($model, 'baggagePerAdult') ?>

    <?php // echo $form->field($model, 'numberOfChild') ?>

    <?php // echo $form->field($model, 'pricePerChild') ?>

    <?php // echo $form->field($model, 'baggagePerChild') ?>

    <?php // echo $form->field($model, 'numberOfInfant') ?>

    <?php // echo $form->field($model, 'pricePerInfant') ?>

    <?php // echo $form->field($model, 'baggagePerInfant') ?>

    <?php // echo $form->field($model, 'totalPrice') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'notes') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'createdAt') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <?php // echo $form->field($model, 'updatedAt') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
