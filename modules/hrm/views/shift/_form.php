<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Department $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="shift-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'entryTime')->textInput(['type' => 'time']) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'exitTime')->textInput(['type' => 'time']) ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
