<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeShift $model */

$this->title = Yii::t('app', 'Create Employee Shift');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-shift-create">
    <?= $this->render('_form', [
        'model' => $model,
        'departmentList' => $departmentList,
    ]) ?>
</div>
