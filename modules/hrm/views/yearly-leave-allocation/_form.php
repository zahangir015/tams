<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\YearlyLeaveAllocation $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="yearly-leave-allocation-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php
            $form = ActiveForm::begin();
            foreach ($types as $key => $type) {
                ?>
                <div class="row">
                    <div class="col-md">
                        <?= $form->field($model, "[$key]year")->textInput() ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, "[$key]leaveTypeId")->dropdownList([$type['id'] => $type['name']], ['value' => $type['id']]) ?>
                    </div>
                    <div class="col-md">
                        <?= $form->field($model, "[$key]numberOfDays")->textInput(['value' => $type['defaultDays']]) ?>
                        <?= $form->field($model, "[$key]status")->hiddenInput(['value' => 1])->label(false) ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
