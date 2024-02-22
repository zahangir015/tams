<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\serviceInventory\models\Amenity;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\AmenitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Amenities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amenity-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Amenity'), ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            'status',
            'createdAt',
            //'createdBy',
            //'updatedAt',
            //'updatedBy',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Amenity $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
