<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Ledger */

$this->title = $model->reference;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ledgers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="ledger-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'date',
            'reference',
            'refId',
            'refModel',
            'subRefId',
            'subRefModel',
            'debit',
            'credit',
            'balance',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',
        ],
    ]) ?>
</div>
