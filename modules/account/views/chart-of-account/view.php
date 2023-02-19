<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
use app\components\Utilities;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ChartOfAccount $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Chart Of Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="chart-of-account-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'uid' => $model->uid], ['class' => 'btn btn-primary']) ?>
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
                        'attribute' => 'accountType',
                        'value' => function ($model) {
                            return $model->accountType->name;
                        },
                    ],
                    [
                        'attribute' => 'accountGroup',
                        'value' => function ($model) {
                            return $model->accountGroup->name;
                        },
                    ],
                    'code',
                    'name',
                    'description',
                    'reportType',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            $labelClass = Utilities::statusLabelClass($model->status);
                            $labelText = ($model->status) ? 'Active' : 'Inactive';
                            return '<span class="right badge ' . $labelClass . '">' . $labelText . '</span>';
                        },
                        'format' => 'html'
                    ],
                    'createdAt',
                    'createdBy',
                    'updatedAt',
                    'updatedBy',
                ],
            ]) ?>
        </div>
    </div>
</div>
