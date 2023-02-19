<?php

use app\components\GlobalConstant;
use app\modules\account\models\ExpenseCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\account\models\ExpenseCategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Expense Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-category-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            //'id',
            //'uid',
            'name',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($model) {
                    $labelClass = \app\components\Utilities::statusLabelClass($model->status);
                    return '<span class="right badge ' . $labelClass . '">' . GlobalConstant::DEFAULT_STATUS[$model->status] . '</span>';
                },
                'filter' => GlobalConstant::DEFAULT_STATUS,
                'format' => 'html',
            ],
            'createdAt',
            'createdBy',
            'updatedAt',
            'updatedBy',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ExpenseCategory $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => \app\components\Utilities::getBasicActionColumnArray()
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/account/expense-category/create'], [
                        'title' => Yii::t('app', 'Add Airline'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/account/expense-category/index'], [
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
