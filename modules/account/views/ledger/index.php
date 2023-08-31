<?php

use app\components\Utilities;
use app\modules\account\components\AccountConstant;
use app\modules\account\models\Ledger;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\account\models\LedgerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ledgers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ledger-index">
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
            'title',
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
                'attribute' => 'ref',
                'label' => 'Ref Name',
                'value' => function ($model) {
                    return Ledger::getReferenceName($model->refId, $model->refModel);
                }
            ],
            [
                'attribute' => 'reference',
            ],
            [
                'attribute' => 'debit',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'credit',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            [
                'attribute' => 'balance',
                'format' => ['decimal', 2],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
            ],
            'createdBy',
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-redo"></i>', ['/account/ledger/index'], [
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
