<?php

use app\modules\hrm\components\HrmConstant;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\LeaveApprovalHistorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Leave Approval Histories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-approval-history-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'employee',
                'value' => function ($model) {
                    return $model->leaveApplication->employee->firstName . ' ' . $model->leaveApplication->employee->lastName;
                }
            ],
            [
                'attribute' => 'leaveType',
                'value' => function ($model) {
                    return $model->leaveApplication->leaveType->name;
                }
            ],
            [
                'attribute' => 'from',
                'value' => function ($model) {
                    return $model->leaveApplication->from;
                }
            ],
            [
                'attribute' => 'to',
                'value' => function ($model) {
                    return $model->leaveApplication->to;
                }
            ],
            [
                'attribute' => 'numberOfDays',
                'value' => function ($model) {
                    return $model->leaveApplication->numberOfDays;
                }
            ],
            'requestedTo',
            'approvalLevel',
            'approvalStatus',
            'remarks',
            [
                'class' => 'kartik\grid\ActionColumn',
                'vAlign' => 'middle',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return \yii\helpers\Url::to([$action, 'uid' => $model->uid]);
                },
                'width' => '150px',
                'template' => '{view} {approve} {cancel}',
                'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fa fa-info-circle"></i>', ['view', 'uid' => $model->uid], [
                            'title' => 'view',
                            'data-pjax' => '0',
                            'class' => 'btn btn-primary btn-xs'
                        ]);
                    },
                    'approve' => function ($url, $model, $key) {
                        if ($model->approvalStatus !== HrmConstant::APPROVAL_STATUS['Approved']) {
                            return Html::a('<i class="fa fa-check-circle"></i>', ['approve', 'uid' => $model->uid],
                                [
                                    'title' => Yii::t('app', 'Approve'),
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-success btn-xs'
                                ]);
                        } else {
                            return false;
                        }
                    },
                    'cancel' => function ($url, $model, $key) {
                        if ($model->approvalStatus !== HrmConstant::APPROVAL_STATUS['Cancelled']) {
                            return Html::a('<i class="fa fa-times-circle"></i>', ['cancel', 'uid' => $model->uid], [
                                'title' => 'Cancel',
                                'data-pjax' => '0',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to cancel this application?'),
                                    'method' => 'post',
                                ],
                                'class' => 'btn btn-danger btn-xs'
                            ]);
                        } else {
                            return false;
                        }
                    },
                ]
            ]
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/account/invoice/create'], [
                        'title' => Yii::t('app', 'Add Category'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/account/invoice/index'], [
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
        'panel' => [
            'heading' => '<i class="fas fa-list-alt"></i> ' . Html::encode($this->title),
            'type' => GridView::TYPE_DARK
        ],
    ]); ?>
</div>
