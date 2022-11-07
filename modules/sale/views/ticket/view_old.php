<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Ticket */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uid',
            'motherTicketId',
            'airlineId',
            'providerId',
            'invoiceId',
            'customerId',
            'customerCategory',
            'paxName',
            'paxType',
            'eTicket',
            'pnrCode',
            'type',
            'tripType',
            'bookedOnline',
            'flightType',
            'seatClass',
            'codeShare',
            'reference',
            'issueDate',
            'departureDate',
            'refundRequestDate',
            'route',
            'numberOfSegment',
            'baseFare',
            'tax',
            'otherTax',
            'commission',
            'commissionReceived',
            'incentive',
            'incentiveReceived',
            'govTax',
            'serviceCharge',
            'ait',
            'quoteAmount',
            'receivedAmount',
            'paymentStatus',
            'costOfSale',
            'netProfit',
            'baggage',
            'status',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',
        ],
    ]) ?>

</div>
