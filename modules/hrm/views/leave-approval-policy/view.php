<?php

use app\components\Utilities;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApprovalPolicy $model */

$this->title = $model->employee->firstName.' '.$model->employee->lastName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Approval Policies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="leave-approval-policy-view">
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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'approvalLevel',
            [
                'attribute' => 'employeeId',
                'value' => function ($model) {
                    return $model->employee->firstName.' '.$model->employee->lastName;
                },
            ],
            [
                'attribute' => 'requestedTo',
                'value' => function ($model) {

                    return $model->requestedEmployee->firstName.' '.$model->requestedEmployee->lastName;
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
