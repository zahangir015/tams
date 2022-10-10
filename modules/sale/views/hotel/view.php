<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\hotel\Hotel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hotel-view">

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
            'motherId',
            'invoiceId',
            'identificationNumber',
            'customerId',
            'customerCategory',
            'voucherNumber',
            'reservationCode',
            'type',
            'issueDate',
            'refundRequestDate',
            'checkInDate',
            'checkOutDate',
            'freeCancellationDate',
            'totalNights',
            'route',
            'isRefundable',
            'quoteAmount',
            'costOfSale',
            'netProfit',
            'receivedAmount',
            'paymentStatus',
            'isOnlineBooked',
            'reference',
            'status',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',
        ],
    ]) ?>

</div>
