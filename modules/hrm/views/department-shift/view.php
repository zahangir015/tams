<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\DepartmentShift $model */

$this->title = $model->department->name.' - '.$model->shift->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Department Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="department-shift-view">
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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'departmentId',
                'value' => function ($model) {
                    return $model->department->name;
                },
            ],
            [
                'attribute' => 'shiftId',
                'value' => function ($model) {
                    return $model->shift->title;
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $labelClass = \app\components\Helper::statusLabelClass($model->status);
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
