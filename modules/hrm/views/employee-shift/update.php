<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeShift $model */

$this->title = Yii::t('app', 'Update Employee Shift: {name}', [
    'name' => $model->employee->firstName,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="employee-shift-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
