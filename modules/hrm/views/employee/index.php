<?php

use app\components\Helper;
use app\modules\hrm\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Employees');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            //'uid',
            //'userId',
            'firstName',
            'lastName',
            'fathersName',
            'mothersName',
            'dateOfBirth',
            'gender',
            'bloodGroup',
            'maritalStatus',
            'religion',
            'nid',
            'officialId',
            'officialEmail:email',
            'officialPhone',
            'permanentAddress',
            'presentAddress',
            'personalEmail:email',
            'personalPhone',
            'contactPersonsName',
            'contactPersonsPhone',
            'contactPersonsAddress',
            'contactPersonsRelation',
            'joiningDate',
            'confirmationDate',
            'inProhibition',
            'jobCategory',
            'reportTo',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Employee $model, $key, $index, $column) {
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
                    Html::a('<i class="fas fa-plus"></i>', ['/hrm/employee/create'], [
                        'title' => Yii::t('app', 'Add Designation'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/hrm/employee/index'], [
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
