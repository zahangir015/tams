<?php

use app\components\GlobalConstant;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \app\modules\sale\models\hotel\HotelCategory $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="hotel-category-form">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <?= Html::encode($this->title) ?>
            </div>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
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
