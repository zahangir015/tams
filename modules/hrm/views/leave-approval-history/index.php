<?php

use app\modules\hrm\models\LeaveApprovalHistory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\hrm\models\search\LeaveApprovalHistorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Leave Approval Histories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-approval-history-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'leaveApplicationId',
            'requestedTo',
            'approvalLevel',
            'approvalStatus',
            //'remarks',
            //'status',
            //'createdBy',
            //'createdAt',
            //'updatedBy',
            //'updatedAt',
            //'uid',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, LeaveApprovalHistory $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
