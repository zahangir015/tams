<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountGroup $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="account-group-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                    'name',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            $labelClass = Helper::statusLabelClass($model->status);
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
