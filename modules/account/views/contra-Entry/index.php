<?php

use app\modules\account\models\ContraEntry;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\account\models\search\ContraEntrySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Contra Entries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contra-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Contra Entry'), ['create'], ['class' => 'btn btn-success']) ?>
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
            'identificationNumber',
            'bankFrom',
            'bankTo',
            //'amount',
            //'paymentDate',
            //'remarks',
            //'status',
            //'createdAt',
            //'updatedAt',
            //'createdBy',
            //'updatedBy',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ContraEntry $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
