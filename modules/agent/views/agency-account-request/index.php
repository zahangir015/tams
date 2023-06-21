<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\agent\models\AgencyAccountRequest;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
/** @var yii\web\View $this */
/** @var app\modules\agent\models\AgencyAccountRequestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Agency Account Requests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-account-request-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, AgencyAccountRequest $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Utilities::getBasicActionColumnArray()
            ],
            'name',
            'designation',
            'company',
            'address',
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
            'phone',
            'email:email',
            'status',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',
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
