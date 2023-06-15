<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\sale\models\HotelProposal;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\search\HotelProposalSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Hotel Proposals');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-proposal-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, HotelProposal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Utilities::getBasicActionColumnArray()
            ],
            'hotelCategoryId',
            'hotelName',
            'hotelAddress',
            'countryId',
            'cityId',
            'numberOfAdult',
            'numberOfChild',
            'amenities:ntext',
            'totalPrice',
            'discount',
        ],
        'toolbar' => WidgetHelper::kartikToolBar(),
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<i class="fas fa-list-alt"></i> ' . Html::encode($this->title),
            'type' => GridView::TYPE_LIGHT
        ],
    ]); ?>

</div>