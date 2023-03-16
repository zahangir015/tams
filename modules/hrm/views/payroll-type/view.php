<?php

use app\components\Utilities;
use app\modules\hrm\components\HrmConstant;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\PayrollType $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payroll Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payroll-type-view">
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
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                [
                    'attribute' => 'amountType',
                    'value' => function ($model) {
                        return HrmConstant::AMOUNT_TYPE[$model->amountType];
                    },
                ],
                [
                    'attribute' => 'calculatingMethod',
                    'value' => function ($model) {
                        return HrmConstant::CALCULATING_METHOD[$model->calculatingMethod];
                    },
                ],
                [
                    'attribute' => 'category',
                    'value' => function ($model) {
                        return HrmConstant::PAYROLL_CATEGORY[$model->category];
                    },
                ],
                'amount',
                'order',
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
