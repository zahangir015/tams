<?php

use app\modules\hrm\models\Designation;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use app\components\GlobalConstant;
use app\components\Helper;
/** @var yii\web\View $this */
/** @var app\modules\hrm\models\DesignationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Designations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="designation-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            //'parentId',
            /*[
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'departmentId',
                'value' => function ($model) {
                    return $model->department->name;
                },
                'filter' => $departments
            ],*/
            [
                'attribute' => 'departmentId',
                'value' => function ($model) {
                    return $model->department->name;
                },
                'filter' => Select2::widget(Helper::ajaxDropDown('departmentId', '/hrm/department/get-departments', false, 'departmentId', 'departmentId'))
            ],
            'name',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($model) {
                    $labelClass = Helper::statusLabelClass($model->status);
                    $labelText = ($model->status) ? 'Active' : 'Inactive';
                    return '<span class="right badge ' . $labelClass . '">' . $labelText . '</span>';
                },
                'filter' => GlobalConstant::DEFAULT_STATUS,
                'format' => 'html',
            ],
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Designation $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {edit} {delete}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => Helper::getBasicActionColumnArray()
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/hrm/designation/create'], [
                        'title' => Yii::t('app', 'Add Designation'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/designation/index'], [
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
            'heading'=> '<i class="fas fa-list-alt"></i> '.Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>

</div>
