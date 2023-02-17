<?php

use app\components\Helper;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeShift $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="employee-shift-form">

    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'departmentId')->dropDownList($departmentList, ['prompt' => 'Select Department ...']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'shiftId')->widget(DepDrop::class, [
                        'data' => $model->isNewRecord ? [] : [$model->shiftId => $model->shift->title],
                        'options' => ['placeholder' => 'Select shift...', 'id' => 'shiftId',],
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => ['pluginOptions' => ['allowClear' => true], 'theme' => Select2::THEME_DEFAULT],
                        'pluginOptions' => [
                            'depends' => ['employeeshift-departmentid'],
                            'initialize' => $model->isNewRecord ? false : true,
                            'url' => Url::to(['/hrm/department-shift/get-shift-by-department']),
                            'loadingText' => 'Loading shift ...',
                        ],
                    ]); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'employeeId')->widget(DepDrop::classname(), [
                        'data' => empty($model->reported) ? [] : [$model->reported->id => $model->reported->firstName . ' ' . $model->reported->lastName],
                        'options' => ['placeholder' => 'Select employee...'],
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => ['pluginOptions' => ['allowClear' => true], 'theme' => Select2::THEME_DEFAULT],
                        'pluginOptions' => [
                            'depends' => ['employeeshift-departmentid'],
                            'initialize' => $model->isNewRecord ? false : true,
                            'url' => Url::to(['/hrm/employee/get-employee-by-department']),
                            'loadingText' => 'Loading Employee ...',
                        ],
                    ]); ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
