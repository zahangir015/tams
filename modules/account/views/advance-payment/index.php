<?php

use app\components\WidgetHelper;
use app\modules\account\models\Ledger;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
/** @var yii\web\View $this */
/** @var app\modules\account\models\search\AdvancePaymentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Customer Advance Payments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advance-payment-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => 'kartik\grid\ActionColumn',
                'vAlign' => 'middle',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-info-circle"></i>', ['view', 'uid' => $model->uid], [
                            'title' => 'view',
                            'data-pjax' => '0',
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },

                ]
            ],
            'identificationNumber',
            [
                'attribute' => 'date',
                'label' => 'Date',
                'format' => 'date',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date',
                    'pluginOptions' => [
                        'format' => 'Y-m-d',
                        'autoUpdateInput' => false
                    ]
                ])
            ],
            [
                'attribute' => 'refId',
                'label' => 'Ref Name',
                'value' => function ($model) {
                    return Ledger::getReferenceName($model->refId, $model->refModel);
                },
                'filter' => kartik\select2\Select2::widget(WidgetHelper::ajaxSelect2Widget('AdvancePaymentSearch[refId]', '/sale/customer/get-customers', false, 'refId', 'refId'))
            ],
            'bankId',
            'paidAmount',
            'remarks:ntext',
            [
                'attribute' => 'paidAmount',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'processedAmount',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],

        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/account/advance-payment/create'], [
                        'title' => Yii::t('app', 'Add Advance Payment'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/account/advance-payment/index'], [
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Reset Grid')
                    ]),
            ],
            '{export}',
            '{toggleData}'
        ],
        'pjax' => false,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'responsiveWrap' => false,
        'hover' => true,
        'showFooter' => true,
        'showPageSummary' => true,
        'panel' => [
            'heading' => '<i class="fas fa-list-alt"></i> ' . Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>
</div>
