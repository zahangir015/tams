<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'reportTo') ?>

    <?= $form->field($model, 'firstName') ?>

    <?php // echo $form->field($model, 'lastName') ?>

    <?php // echo $form->field($model, 'fathersName') ?>

    <?php // echo $form->field($model, 'mothersName') ?>

    <?php // echo $form->field($model, 'dateOfBirth') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'bloodGroup') ?>

    <?php // echo $form->field($model, 'maritalStatus') ?>

    <?php // echo $form->field($model, 'religion') ?>

    <?php // echo $form->field($model, 'nid') ?>

    <?php // echo $form->field($model, 'officialId') ?>

    <?php // echo $form->field($model, 'officialEmail') ?>

    <?php // echo $form->field($model, 'officialPhone') ?>

    <?php // echo $form->field($model, 'permanentAddress') ?>

    <?php // echo $form->field($model, 'presentAddress') ?>

    <?php // echo $form->field($model, 'personalEmail') ?>

    <?php // echo $form->field($model, 'personalPhone') ?>

    <?php // echo $form->field($model, 'contactPersonsName') ?>

    <?php // echo $form->field($model, 'contactPersonsPhone') ?>

    <?php // echo $form->field($model, 'contactPersonsAddress') ?>

    <?php // echo $form->field($model, 'contactPersonsRelation') ?>

    <?php // echo $form->field($model, 'joiningDate') ?>

    <?php // echo $form->field($model, 'confirmationDate') ?>

    <?php // echo $form->field($model, 'inProhibition') ?>

    <?php // echo $form->field($model, 'jobCategory') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'createdAt') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <?php // echo $form->field($model, 'updatedAt') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
