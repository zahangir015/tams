<?php

use app\components\Utilities;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ContraEntry $model */

$this->title = $model->identificationNumber;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contra Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="contra-entry-view">
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
                    'identificationNumber',
                    [
                        'attribute' => 'bankFrom',
                        'value' => function ($model) {
                            return $model->transferredFrom->name;
                        }
                    ],
                    [
                        'attribute' => 'bankFrom',
                        'value' => function ($model) {
                            return $model->transferredTo->name;
                        }
                    ],
                    'amount',
                    'paymentDate',
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
                    'createdAt',
                    'updatedAt',
                    'createdBy',
                    'updatedBy',
                ],
            ]) ?>
        </div>
    </div>

</div>
