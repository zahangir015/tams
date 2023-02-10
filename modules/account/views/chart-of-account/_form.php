<?php

use app\modules\account\components\AccountConstant;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\components\Helper;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ChartOfAccount $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="chart-of-account-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'reportType')->dropdownList(AccountConstant::REPORT_TYPE, ['maxlength' => true]) ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'accountTypeId')->widget(Select2::class, Helper::ajaxDropDown('accountTypeId', '/account/account-type/search', true, 'accountTypeId', 'accountTypeId', ($model->isNewRecord) ? [] : [$model->accountType->id => $model->accountType->name])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'accountGroupId')->widget(DepDrop::class, Helper::depDropConfigurationGenerate($model, 'accountGroupId', 'accountTypeId', '/account/account-group/get-group-by-type', $model->isNewRecord ? [] : [$model->accountGroup->id => $model->accountGroup->name])); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
