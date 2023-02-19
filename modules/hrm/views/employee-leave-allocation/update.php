<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeLeaveAllocation $model */

$this->title = Yii::t('app', 'Update Employee Leave Allocation: {name}', [
    'name' => $model->employee->firstName.' - '.$model->leaveType->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Leave Allocations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->employee->firstName.' - '.$model->leaveType->name, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="employee-leave-allocation-update">
    <?= $this->render('_form', [
        'model' => $model,
        'types' => $types,
    ]) ?>
</div>
