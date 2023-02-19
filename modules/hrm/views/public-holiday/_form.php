<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\components\Utilities;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\PublicHoliday $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="public-holiday-form">
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
                    <?= $form->field($model, 'date')->widget(DatePicker::class, Utilities::getDateWidget('date', 'date')) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
