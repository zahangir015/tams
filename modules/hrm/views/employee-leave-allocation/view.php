<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveAllocation $model */

$this->title = $model->employee->firstName . ' - ' . $model->leaveType->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Leave Allocations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="employee-leave-allocation-view">
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
                            return $model->employee->firstName.' '.$model->employee->lastName;
                        },
                    ],
                    [
                        'attribute' => 'leaveTypeId',
                        'value' => function ($model) {
                            return $model->leaveType->name;
                        },
                    ],
                    'year',
                    'totalDays',
                    'availedDays',
                    'remainingDays',
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
