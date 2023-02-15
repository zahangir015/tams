<?php

use app\modules\account\components\ServiceConstant;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\bootstrap4\ActiveForm;
use app\components\Helper;


/** @var yii\web\View $this */
/** @var app\modules\account\models\Journal $model */
/** @var yii\bootstrap4\ActiveForm $form */
$this->registerJsFile(
    '@web/js/journal.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="journal-form">
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title"><?= Html::encode($this->title) ?></div>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md">
                    <?= $form->field($model, 'postedDate')->widget(DatePicker::class, Helper::getDatewidget('postedDate')); ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'journalNumber')->textInput(['maxlength' => true, 'readOnly' => true, 'value' => ($model->isNewRecord) ? Helper::getJournalNumber() : $model->journalNumber]) ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'outOfBalance')->textInput(['type' => 'number', 'readOnly' => true]) ?>
                </div>
            </div>

            <div class="journal-entry">
                <h5>Journal Entries</h5>
                <hr>
                <?php
                for ($itenary = 0; $itenary < 4; $itenary++) {
                    ?>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($journalEntry, "[$itenary]accountId")->widget(Select2::class, Helper::ajaxDropDown('accountId', '/account/chart-of-account/search', false, 'accountId' . $itenary, 'accountId'))->label(!$itenary ? 'Chart Of Account' : false) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($journalEntry, "[$itenary]refModel")->dropDownList(ServiceConstant::REF_MODEL, ['id' => 'refModel' . $itenary, 'class' => 'form-control refModel', 'prompt' => ''])->label(!$itenary ? 'Reference Type' : false); ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($journalEntry, "[$itenary]refId")->widget(DepDrop::class, Helper::depDropConfigurationGenerate($journalEntry, 'refId' . $itenary, 'refModel' . $itenary, '/account/journal/get-reference'))->label(!$itenary ? 'Reference' : false) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($journalEntry, "[$itenary]details")->textInput()->label(!$itenary ? 'Details' : false) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($journalEntry, "[$itenary]debit")->textInput(['type' => 'number', 'value' => 0, 'class' => 'form-control debit'])->label(!$itenary ? 'Debit' : false) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($journalEntry, "[$itenary]credit")->textInput(['type' => 'number', 'value' => 0, 'class' => 'form-control credit'])->label(!$itenary ? 'Credit' : false) ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <strong>Total</strong>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'debit')->textInput(['readOnly' => true]) ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'credit')->textInput(['readOnly' => true]) ?>
                    </div>
                </div>
            </div>
            <div class="form-group mt-5">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
