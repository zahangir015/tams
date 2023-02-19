<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\sale\models\Supplier;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\search\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'name',
            'email:email',
            'company',
            'address',
            'phone',
            'refundCharge',
            'reissueCharge',
            'categories',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'type',
                'value' => function ($model) {
                    $labelClass = Utilities::typeLabelClass($model->status);
                    return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::SUPPLIER_TYPE[$model->type] . '</span>';
                },
                'filter' => GlobalConstant::SUPPLIER_TYPE,
                'format' => 'html',
            ],

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
                'urlCreator' => function ($action, Supplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                 },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-info-circle"></i>', ['view', 'uid' => $model->uid], [
                            'title' => 'view',
                            'data-pjax' => '0',
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                    'edit' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-envelope-open"></i>', ['update', 'uid' => $model->uid], [
                            'title' => Yii::t('app', 'Preview of Invoice'),
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-trash-alt"></i>', ['delete', 'uid' => $model->uid], [
                            'title' => 'delete',
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                ]
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/supplier/create'], [
                        'title' => Yii::t('app', 'Add Category'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/sale/supplier/index'], [
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
            'heading'=> '<i class="fas fa-list-alt"></i> '.Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>


</div>
