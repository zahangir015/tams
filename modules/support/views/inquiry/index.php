<?php

use app\components\Utilities;
use app\components\WidgetHelper;
use app\modules\support\models\Inquiry;
use app\modules\support\SupportConstant;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\support\models\InquirySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Inquiries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inquiry-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Inquiry $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Utilities::getBasicActionColumnArray()
            ],
            [
                'attribute' => 'identificationNumber',
                'value' => function($model){
                    return $model->identificationNumber;
                },
                'label' => 'Identification #'
            ],
            'name',
            'subject',
            'company',
            'phone',
            'email:email',
            [
                'attribute' => 'source',
                'value' => function($model){
                    return $model->source;
                },
                'filter' => SupportConstant::QUERY_SOURCE
            ],
            [
                    'attribute' => 'status',
                'value' => function($model){
                    return SupportConstant::QUERY_STATUS[$model->status];
                },
                'filter' => SupportConstant::QUERY_STATUS
            ],
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
