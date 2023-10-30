<?php

use app\modules\sale\models\Customer;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AdvancePayment $model */

$this->title = $model->identificationNumber;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advance Payments'), 'url' => ($model->refModel == Customer::class) ? ['index'] : ['supplier-advance-payment']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="advance-payment-view">
    <div class="card">
        <div class="card-header bg-gray-dark">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'identificationNumber',
                    'refId',
                    'refModel',
                    'bankId',
                    'date',
                    'paidAmount',
                    'processedAmount',
                    'remarks:ntext',
                    'status',
                    'createdBy',
                    'createdAt',
                    'updatedBy',
                    'updatedAt',
                ],
            ]) ?>
        </div>
    </div>
</div>
