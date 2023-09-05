<?php

use app\components\GlobalConstant;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Supplier */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="supplier-form">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'type')->dropdownList(GlobalConstant::SUPPLIER_TYPE, ['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'reissueCharge')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'refundCharge')->textInput() ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'status')->dropdownList(GlobalConstant::DEFAULT_STATUS) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'balance')->textInput(['type' => 'number', 'min' => 0, 'step' => 'any', 'value' => 0]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'categories')->widget(Select2::classname(), [
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'data' => $categories,
                        'options' => ['placeholder' => 'Select Category ...', 'multiple' => true, 'value' => $model->isNewRecord ? [] : Json::decode($model->categories)],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10
                        ],
                    ])->label('Supplier Category'); ?>
                    <?php echo '<div class="hint-block">' . 'if not available create a new one first ' . Html::a(Yii::t('app', 'Add category'), ['supplier-category/create'], ['class' => 'small-box-footer', 'target' => '_blank']) . '</div>'; ?>

                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', ($model->isNewRecord) ? 'Save' : 'Update'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
