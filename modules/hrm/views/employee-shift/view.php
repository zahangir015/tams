<?php

use app\components\Utilities;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeShift $model */

$this->title = $model->employee->firstName.' '.$model->employee->lastName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="employee-shift-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'uid' => $model->uid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'uid' => $model->uid], [
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
                        'attribute' => 'departmentId',
                        'value' => function ($model) {
                            return $model->department ? $model->department->name : null;
                        },
                    ],
                    [
                        'attribute' => 'shiftId',
                        'value' => function ($model) {
                            return $model->shift->title;
                        },
                    ],
                    [
                        'attribute' => 'employeeId',
                        'value' => function ($model) {
                            return $model->employee->firstName.' '.$model->employee->lastName;
                        },
                    ],
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

</div>
