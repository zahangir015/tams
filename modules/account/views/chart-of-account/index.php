<?php

use app\components\GlobalConstant;
use app\modules\account\models\ChartOfAccount;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utilities;

/** @var yii\web\View $this */
/** @var app\modules\account\models\search\ChartOfAccountSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Chart Of Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chart-of-account-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'accountTypeId',
                'value' => function ($model) {
                    return $model->accountType->name;
                },
                'filter' => Select2::widget(Utilities::ajaxDropDown('accountTypeId', '/account/account-type/get-types', false, 'accountTypeId', 'accountTypeId'))
            ],
            [
                'attribute' => 'accountGroupId',
                'value' => function ($model) {
                    return $model->accountGroup->name;
                },
                'filter' => Select2::widget(Utilities::ajaxDropDown('accountGroupId', '/account/account-group/get-groups', false, 'accountGroupId', 'accountGroupId'))
            ],
            'name',
            'code',
            'reportType',
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
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, ChartOfAccount $model, $key, $index, $column) {
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
                    Html::a('<i class="fas fa-plus"></i>', ['/account/chart-of-account/create'], [
                        'title' => Yii::t('app', 'Add Airline'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/account/chart-of-account/index'], [
                        'class' => 'btn btn-primary',
                        'title' => Yii::t('app', 'Reset Grid')
                    ]),
            ],
            '{export}',
            '{toggleData}'
        ],
        //'pjax' => true,
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
