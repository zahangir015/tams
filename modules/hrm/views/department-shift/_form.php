<?php

use app\components\GlobalConstant;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\components\Helper;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\DepartmentShift $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="department-shift-form">
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
                    <?= $form->field($model, 'shiftId')->widget(Select2::class, Helper::ajaxDropDown('shiftId', '/hrm/shift/get-shifts', true, 'shiftId', 'shiftId', $model->isNewRecord ? [] : [$model->shift->id => $model->shift->title])) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
