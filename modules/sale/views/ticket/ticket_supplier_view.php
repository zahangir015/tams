<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\ticket\TicketSupplier $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Suppliers'), 'url' => ['ticket-supplier-list']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-supplier-view">

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
            'motherId',
            [
                'attribute' => 'supplier',
                'value' => function ($model) {
                    return $model->supplier->name;
                },
                'label' => 'Customer',
            ],
            [
                'attribute' => 'airline',
                'value' => function ($model) {
                    return $model->airline->name;
                },
                'label' => 'Airline',
            ],
            [
                'attribute' => 'bill',
                'value' => function ($model) {
                    return ($model->bill) ? $model->bill->billNumber : null;
                },
                'label' => 'Bill Number',
            ],
            'issueDate',
            'refundRequestDate',
            'eTicket',
            'pnrCode',
            'type',
            'baseFare',
            'tax',
            'otherTax',
            'costOfSale',
            'paidAmount',
            'paymentStatus',
            'status',
            'serviceCharge',

        ],
    ]) ?>

</div>
