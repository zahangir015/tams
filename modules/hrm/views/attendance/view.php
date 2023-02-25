<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Attendance $model */

$this->title = $model->employee->firstName.''.$model->employee->lastName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attendances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="attendance-view">
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
            'employeeId',
            'shiftId',
            'leaveTypeId',
            'leaveApplicationId',
            'rosterId',
            'date',
            'entry',
            'exit',
            'isAbsent',
            'isLate',
            'isEarlyOut',
            'totalLateInTime',
            'totalEarlyOutTime',
            'totalWorkingHours',
            'overTime',
            'remarks',
            'employeeNote',
            'status',
            'createdBy',
            'updatedBy',
            'createdAt',
            'updatedAt',
        ],
    ]) ?>

</div>
