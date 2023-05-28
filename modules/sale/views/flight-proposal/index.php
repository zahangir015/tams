<?php

use app\modules\sale\models\FlightProposal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\sale\models\search\FlightProposalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Flight Proposals');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flight-proposal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Flight Proposal'), ['create'], ['class' => 'btn btn-success']) ?>
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
            'agencyId',
            'airlineId',
            'class',
            //'tripType',
            //'route',
            //'departure',
            //'arrival',
            //'numberOfAdult',
            //'pricePerAdult',
            //'baggagePerAdult',
            //'numberOfChild',
            //'pricePerChild',
            //'baggagePerChild',
            //'numberOfInfant',
            //'pricePerInfant',
            //'baggagePerInfant',
            //'totalPrice',
            //'discount',
            //'notes:ntext',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, FlightProposal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
