<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeShift $model */

$this->title = Yii::t('app', 'Update Employee Shift: {name}', [
    'name' => $model->employee->firstName.' '.$model->employee->lastName,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->employee->firstName.' '.$model->employee->lastName, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="employee-shift-update">
    <?= $this->render('_form', [
        'model' => $model,
        'departmentList' => $departmentList,
    ]) ?>

</div>
