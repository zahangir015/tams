<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\search\HotelProposalSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="hotel-proposal-search">

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

    <?= $form->field($model, 'hotelCategoryId') ?>

    <?= $form->field($model, 'hotelName') ?>

    <?php // echo $form->field($model, 'hotelAddress') ?>

    <?php // echo $form->field($model, 'countryId') ?>

    <?php // echo $form->field($model, 'cityId') ?>

    <?php // echo $form->field($model, 'numberOfAdult') ?>

    <?php // echo $form->field($model, 'numberOfChild') ?>

    <?php // echo $form->field($model, 'amenities') ?>

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
