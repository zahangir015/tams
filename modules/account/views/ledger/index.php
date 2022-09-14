<?php

use app\modules\account\models\Ledger;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\account\models\LedgerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ledgers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ledger-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ledger'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'title',
            'date',
            'reference',
            'refId',
            'refModel',
            'subRefId',
            'subRefModel',
            'debit',
            'credit',
            'balance',
            'status',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Ledger $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
