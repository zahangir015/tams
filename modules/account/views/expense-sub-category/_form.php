<?php

use app\components\GlobalConstant;
use app\components\Helper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ExpenseSubCategory $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="expense-sub-category-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'categoryId')->widget(Select2::classname(), Helper::ajaxDropDown('categoryId', '/account/expense-category/get-categories', true, 'categoryId', 'categoryId', ($model->isNewRecord) ? [] : [$model->category->id => $model->category->name]))->label('Category'); ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'status')->dropdownList(GlobalConstant::DEFAULT_STATUS) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
