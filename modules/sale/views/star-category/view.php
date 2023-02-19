<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\StarCategory $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Star Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="star-category-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /*= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) */?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'uid',
            'name',
            'level',
            'pointRange',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $labelClass = Utilities::statusLabelClass($model->status);
                    return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::DEFAULT_STATUS[$model->status] . '</span>';
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
