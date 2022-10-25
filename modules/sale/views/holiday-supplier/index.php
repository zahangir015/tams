<?php

use app\modules\sale\models\holiday\HolidaySupplier;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\sale\models\holiday\HolidaySupplierSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Holiday Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Holiday Supplier'), ['create'], ['class' => 'btn btn-success']) ?>
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
            'holidayId',
            'billId',
            'supplierId',
            //'supplierRef',
            //'issueDate',
            //'departureDate',
            //'refundRequestDate',
            //'type',
            //'serviceDetails',
            //'quantity',
            //'unitPrice',
            //'costOfSale',
            //'paidAmount',
            //'paymentStatus',
            //'status',
            //'holidayCategoryId',
            //'description:ntext',
            //'motherId',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, HolidaySupplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
