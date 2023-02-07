<?php

use app\components\Helper;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\Expense $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="expense-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'categoryId')->widget(Select2::class, Helper::ajaxDropDown('categoryId', '/account/expense-category/get-categories', true, 'categoryId', 'categoryId', $model->isNewRecord ? [] : [$model->category->id => $model->category->name])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'subCategoryId')->widget(DepDrop::class, Helper::depDropConfigurationGenerate($model, 'subCategoryId', 'categoryId', '/account/expense/get-sub-category-by-category', $model->isNewRecord ? [] : [$model->subCategory->id => $model->subCategory->name])); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'supplierId')->widget(Select2::class, Helper::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers', false, 'supplierId', 'supplierId', $model->isNewRecord ? [] : (isset($model->supplier) ? [$model->supplier->id => $model->supplier->name] : []))) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'accruingMonth')->widget(DatePicker::class, Helper::getDateWidget('accruingMonth', 'accruingMonth')) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'timingOfExp')->dropDownList(['Monthly' => 'Monthly', 'Prepaid' => 'Prepaid', 'Accrued' => 'Accrued',], ['prompt' => '']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'totalCost')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
