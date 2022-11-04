<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\visa\VisaSupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Visa Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visa-supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Visa Supplier'), ['create'], ['class' => 'btn btn-success']) ?>
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
            'motherVisaSupplierId',
            'visaId',
            'billId',
            //'countryId',
            //'supplierId',
            //'supplierRef',
            //'paxName',
            //'issueDate',
            //'refundRequestDate',
            //'type',
            //'serviceDetails',
            //'quantity',
            //'unitPrice',
            //'costOfSale',
            //'securityDeposit',
            //'paidAmount',
            //'paymentStatus',
            //'status',
            //'motherId',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, VisaSupplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
