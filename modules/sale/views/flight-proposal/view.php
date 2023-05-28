<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\FlightProposal $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Flight Proposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="flight-proposal-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'id',
            'uid',
            'agencyId',
            'airlineId',
            'class',
            'tripType',
            'route',
            'departure',
            'arrival',
            'numberOfAdult',
            'pricePerAdult',
            'baggagePerAdult',
            'numberOfChild',
            'pricePerChild',
            'baggagePerChild',
            'numberOfInfant',
            'pricePerInfant',
            'baggagePerInfant',
            'totalPrice',
            'discount',
            'notes:ntext',
            'status',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',
        ],
    ]) ?>

</div>
