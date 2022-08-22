<?php

use app\components\GlobalConstant;
use app\components\Helper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Airline */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="airline-form">

    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'supplierId')->widget(Select2::classname(), Helper::ajaxDropDown('supplierId', '/sale/supplier/get-suppliers',  true, 'supplierId', 'supplierId', ($model->isNewRecord) ? [] : [$model->supplierId => $model->supplier->name . ' | ' . $model->supplier->company]))->label('Supplier') ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'commission')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'incentive')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'govTax')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'serviceCharge')->textInput() ?>
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
