<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\search\RefundTransactionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="refund-transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'refId') ?>

    <?= $form->field($model, 'refModel') ?>

    <?= $form->field($model, 'payableAmount') ?>

    <?php // echo $form->field($model, 'receivableAmount') ?>

    <?php // echo $form->field($model, 'totalAmount') ?>

    <?php // echo $form->field($model, 'paymentStatus') ?>

    <?php // echo $form->field($model, 'adjustedAmount') ?>

    <?php // echo $form->field($model, 'isAdjusted') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'createdAt') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <?php // echo $form->field($model, 'updatedAt') ?>

    <?php // echo $form->field($model, 'identificationNumber') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
