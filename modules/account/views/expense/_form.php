<?php

use app\components\Helper;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\Expense $model */
/** @var yii\widgets\ActiveForm $form */
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
                    <?= $form->field($model, 'categoryId')->widget(Select2::class, Helper::ajaxDropDown('categoryId', '/account/expense-category/get-categories', true, 'categoryId', 'categoryId')) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'subCategoryId')->widget(DepDrop::class, [
                        'data' => $model->isNewRecord ? [] : [$model->category->id => $model->category->name],
                        'options' => ['placeholder' => 'Select category...', 'id' => 'subCategoryId',],
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => ['pluginOptions' => ['allowClear' => true], 'theme' => Select2::THEME_DEFAULT],
                        'pluginOptions' => [
                            'depends' => ['expense-categoryid'],
                            'initialize' => $model->isNewRecord ? false : true,
                            'url' => Url::to(['/hrm/employee/get-designation-by-department']),
                            'loadingText' => 'Loading designation ...',
                        ],
                    ]); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'subCategoryId')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'supplierId')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'accruingMonth')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'timingOfExp')->dropDownList(['Monthly' => 'Monthly', 'Prepaid' => 'Prepaid', 'Accrued' => 'Accrued',], ['prompt' => '']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'totalCost')->textInput(['maxlength' => true]) ?>
                </div>
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
