<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeePayroll $model */

$this->title = Yii::t('app', 'Employee Payroll');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Payrolls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-payroll-create">
    <?= $this->render('_form', [
        'model' => $model,
        'employeePayrollTypeDetail' => $employeePayrollTypeDetail,
        'payrollList' => $payrollList,
    ]) ?>

</div>
