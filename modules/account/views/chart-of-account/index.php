<?php

use app\modules\account\models\ChartOfAccount;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\account\models\search\ChartOfAccountSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Chart Of Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chart-of-account-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Chart Of Account'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'accountTypeId',
            'accountGroupId',
            'code',
            //'name',
            //'description',
            //'reportType',
            //'status',
            //'createdAt',
            //'createdBy',
            //'updatedAt',
            //'updatedBy',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ChartOfAccount $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
