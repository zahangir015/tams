<?php

use app\components\Utilities;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApplication $model */

$this->title = $model->leaveType->name.' Leave - '.date('D, jS M, Y', strtotime($model->from));
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Applications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="leave-application-view">
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
            'numberOfDays',
            'from:date',
            'to:date',
            'availableFrom',
            'description:ntext',
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

    <div class="col-md-12">
        <p class="lead">Approval History</p>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>Requested To</th>
                <th>Approval Label</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>

            <?php
                foreach ($model->leaveApprovalHistories as $approvalHistory){
                    ?>
                    <tr>
                        <td><?= $approvalHistory->requested->firstName ?></td>
                        <td><?= $approvalHistory->approvalLevel ?></td>
                        <td><?= $approvalHistory->approvalStatus ?></td>
                        <td><?= $approvalHistory->remarks ?></td>
                    </tr>
            <?php
                }
            ?>

            </tbody>
        </table>
    </div>



</div>
