<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\sale\models\Customer;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\search\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'name',
            'company',
            'customerCode',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'category',
                'value' => function ($model) {
                    return '<span class="right badge badge-default">' . $model->category . '</span>';
                },
                'filter' => GlobalConstant::CUSTOMER_CATEGORY,
                'format' => 'html',
            ],
            'email:email',
            'address',
            'phone',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'creditModality',
                'value' => function ($model) {
                    $labelClass = Utilities::typeLabelClass($model->creditModality);
                    return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::YES_NO[$model->creditModality] . '</span>';
                },
                'filter' => GlobalConstant::YES_NO,
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
                'urlCreator' => function ($action, Customer $model, $key, $index, $column) {
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
                    Html::a('<i class="fas fa-plus"></i>', ['/sale/customer/create'], [
                        'title' => Yii::t('app', 'Add Customer'),
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
            'heading' => '<i class="fas fa-list-alt"></i> ' . Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>
</div>
