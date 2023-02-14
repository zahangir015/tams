<?php

use app\modules\hrm\components\HrmConstant;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\components\Helper;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Weekend $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="weekend-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'departmentId')->widget(Select2::class, Helper::ajaxDropDown('departmentId', '/hrm/department/get-departments', true, 'departmentId', 'departmentId', $model->isNewRecord ? [] : [$model->department->id => $model->department->name])) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'day')->dropdownList(HrmConstant::DAYS, ['maxlength' => true]) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
