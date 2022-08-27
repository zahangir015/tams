<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\search\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tickets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ticket'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'motherTicketId',
            'airlineId',
            'providerId',
            //'invoiceId',
            //'customerId',
            //'customerCategory',
            //'paxName',
            //'paxType',
            //'eTicket',
            //'pnrCode',
            //'type',
            //'tripType',
            //'bookedOnline',
            //'flightType',
            //'seatClass',
            //'codeShare',
            //'reference',
            //'issueDate',
            //'departureDate',
            //'refundRequestDate',
            //'route',
            //'numberOfSegment',
            //'baseFare',
            //'tax',
            //'otherTax',
            //'commission',
            //'commissionReceived',
            //'incentive',
            //'incentiveReceived',
            //'govTax',
            //'serviceCharge',
            //'ait',
            //'quoteAmount',
            //'receivedAmount',
            //'paymentStatus',
            //'costOfSale',
            //'netProfit',
            //'baggage',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
