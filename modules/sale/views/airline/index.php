<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\sale\models\Airline;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\search\AirlineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Airlines');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="airline-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'supplierId',
            'code',
            'name',
            'commission',
            'incentive',
            'govTax',
            'serviceCharge',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($model) {
                    $labelClass = Utilities::statusLabelClass($model->status);
                    return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::DEFAULT_STATUS[$model->status] . '</span>';
                },
                'filter' => GlobalConstant::DEFAULT_STATUS,
                'format' => 'html',
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Airline $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                 },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Utilities::getBasicActionColumnArray()
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/airline/create'], [
                        'title' => Yii::t('app', 'Add Airline'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/airline/index'], [
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Reset Grid')
                    ]),
            ],
            '{export}',
            '{toggleData}'
        ],
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
