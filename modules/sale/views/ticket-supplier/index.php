<?php

use app\modules\sale\models\ticket\TicketSupplier;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var \app\modules\sale\models\ticket\TicketSupplierSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Ticket Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ticket Supplier'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'ticketId',
            'supplierId',
            'airlineId',
            //'billId',
            //'issueDate',
            //'refundRequestDate',
            //'eTicket',
            //'pnrCode',
            //'type',
            //'baseFare',
            //'tax',
            //'otherTax',
            //'costOfSale',
            //'paidAmount',
            //'paymentStatus',
            //'status',
            //'serviceCharge',
            //'motherId',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TicketSupplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
