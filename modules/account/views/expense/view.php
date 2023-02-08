<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\account\models\Expense $model */

$this->title = $model->identificationNumber;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="expense-view">
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
                    'identificationNumber',
                    [
                        'attribute' => 'categoryId',
                        'value' => function ($model) {
                            return $model->category->name;
                        }
                    ],
                    [
                        'attribute' => 'subCategoryId',
                        'value' => function ($model) {
                            return $model->subCategory->name;
                        }
                    ],
                    [
                        'attribute' => 'supplierId',
                        'value' => function ($model) {
                            return isset($model->supplier) ? $model->supplier->name : null;
                        }
                    ],
                    'accruingMonth',
                    'timingOfExp',
                    'totalCost',
                    'totalPaid',
                    'paymentStatus',
                    'notes:ntext',
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
                    'updatedAt',
                    'createdBy',
                    'updatedBy',
                ],
            ]) ?>
        </div>
    </div>

</div>
