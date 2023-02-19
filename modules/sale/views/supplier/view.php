<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Supplier */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="supplier-view">

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
                    'name',
                    'email:email',
                    'company',
                    'address',
                    'phone',
                    'refundCharge',
                    'reissueCharge',
                    'categories',
                    [
                        'attribute' => 'type',
                        'value' => function ($model) {
                            $labelClass = Utilities::typeLabelClass($model->status);
                            return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::SUPPLIER_TYPE[$model->type] . '</span>';
                        },
                        'format' => 'html',
                    ],

                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            $labelClass = Utilities::statusLabelClass($model->status);
                            return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::DEFAULT_STATUS[$model->status] . '</span>';
                        },
                        'format' => 'html',
                    ],
                    'createdBy',
                    'createdAt',
                    'updatedBy',
                    'updatedAt',
                ],
            ]) ?>
        </div>
    </div>
</div>
