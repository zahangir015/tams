<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\PayslipSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="payslip-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'employeeId') ?>

    <?= $form->field($model, 'month') ?>

    <?= $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'gross') ?>

    <?php // echo $form->field($model, 'tax') ?>

    <?php // echo $form->field($model, 'lateFine') ?>

    <?php // echo $form->field($model, 'totalAdjustment') ?>

    <?php // echo $form->field($model, 'totalDeduction') ?>

    <?php // echo $form->field($model, 'totalPaid') ?>

    <?php // echo $form->field($model, 'paymentMode') ?>

    <?php // echo $form->field($model, 'processStatus') ?>

    <?php // echo $form->field($model, 'remarks') ?>

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
