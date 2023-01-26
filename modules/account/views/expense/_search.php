<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ExpenseSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expense-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'categoryId') ?>

    <?= $form->field($model, 'subCategoryId') ?>

    <?= $form->field($model, 'supplierId') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'accruingMonth') ?>

    <?php // echo $form->field($model, 'timingOfExp') ?>

    <?php // echo $form->field($model, 'notes') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'createdAt') ?>

    <?php // echo $form->field($model, 'updatedAt') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
