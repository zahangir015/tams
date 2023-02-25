<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\AttendanceSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="attendance-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'employeeId') ?>

    <?= $form->field($model, 'shiftId') ?>

    <?= $form->field($model, 'leaveTypeId') ?>

    <?php // echo $form->field($model, 'leaveApplicationId') ?>

    <?php // echo $form->field($model, 'rosterId') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'entry') ?>

    <?php // echo $form->field($model, 'exit') ?>

    <?php // echo $form->field($model, 'isAbsent') ?>

    <?php // echo $form->field($model, 'isLate') ?>

    <?php // echo $form->field($model, 'isEarlyOut') ?>

    <?php // echo $form->field($model, 'totalLateInTime') ?>

    <?php // echo $form->field($model, 'totalEarlyOutTime') ?>

    <?php // echo $form->field($model, 'totalWorkingHours') ?>

    <?php // echo $form->field($model, 'overTime') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'employeeNote') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <?php // echo $form->field($model, 'createdAt') ?>

    <?php // echo $form->field($model, 'updatedAt') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
