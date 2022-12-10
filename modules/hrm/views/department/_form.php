<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Department $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="department-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'parentId')->dropdownList($departments, ['maxlength' => true, 'prompt' => 'Select parent department ...', 'value' => $model->isNewRecord ? [] : ($model->parent ? [$model->parent->id => $model->parent->name] : [])]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'status')->dropdownList(GlobalConstant::DEFAULT_STATUS) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
