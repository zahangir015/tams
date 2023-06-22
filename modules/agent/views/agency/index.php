<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\agent\models\Agency;
use app\modules\agent\models\Plan;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\agent\models\search\AgencySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Agencies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Agency $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Utilities::getBasicActionColumnArray()
            ],
            [
                'attribute' => 'planId',
                'value' => function($model){
                    return $model->plan->name;
                },
                'filter' => ArrayHelper::map(Plan::find()->select(['id', 'name'])->where(['status' => GlobalConstant::ACTIVE_STATUS])->all(), 'id', 'name')
            ],
            'agentCode',
            'company',
            [
                'attribute' => 'country',
                'value' => function($model){
                    return $model->country->name;
                },
            ],
            [
                'attribute' => 'city',
                'value' => function($model){
                    return $model->city->name;
                },
            ],
            'address',
            'phone',
            'email:email',
            'timeZone',
            'currency',
            'title',
            'firstName',
            'lastName',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',

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
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>

</div>
