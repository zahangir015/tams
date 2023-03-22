<?php

use app\components\Utilities;
use app\modules\hrm\components\HrmConstant;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeePayroll $model */

$this->title = $model->employee->firstName . ' ' . $model->employee->lastName . ' Payroll';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Payrolls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

?>
<div class="employee-payroll-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'employeeId',
                        'value' => function ($model) {
                            return $model->employee->firstName . ' ' . $model->employee->lastName;
                        },
                    ],
                    'gross',
                    'tax',
                    'paymentMode',
                    'remarks',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            $labelClass = Utilities::statusLabelClass($model->status);
                            $labelText = ($model->status) ? 'Active' : 'Inactive';
                            return '<span class="right badge ' . $labelClass . '">' . $labelText . '</span>';
                        },
                        'format' => 'html'
                    ],
                    'createdBy',
                    'createdAt',
                    'updatedBy',
                    'updatedAt',
                ],
            ]) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title . ' Type Details') ?>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <th>Payroll Type</th>
                    <th>Category</th>
                    <th>Calculating Method</th>
                    <th>Amount Type</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                <?php
                foreach ($model->employeePayrollTypeDetails as $key => $detail) {
                    ?>
                        <tr>
                            <td><?= $detail->payrollType->name ?></td>
                            <td><?= HrmConstant::PAYROLL_CATEGORY[$detail->payrollType->category] ?></td>
                            <td><?= HrmConstant::CALCULATING_METHOD[$detail->payrollType->calculatingMethod] ?></td>
                            <td><?= HrmConstant::AMOUNT_TYPE[$detail->payrollType->amountType] ?></td>
                            <td><?= $detail->amount ?></td>
                        </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
