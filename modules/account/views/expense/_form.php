<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\Expense $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expense-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'categoryId')->textInput() ?>

    <?= $form->field($model, 'subCategoryId')->textInput() ?>

    <?= $form->field($model, 'supplierId')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'accruingMonth')->textInput() ?>

    <?= $form->field($model, 'timingOfExp')->dropDownList([ 'Monthly' => 'Monthly', 'Prepaid' => 'Prepaid', 'Accrued' => 'Accrued', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'createdAt')->textInput() ?>

    <?= $form->field($model, 'updatedAt')->textInput() ?>

    <?= $form->field($model, 'createdBy')->textInput() ?>

    <?= $form->field($model, 'updatedBy')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
