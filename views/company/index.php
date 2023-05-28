<?php

use app\models\Company;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'name',
            'shortName',
            'phone',
            'email:email',
            'address',
            'logo',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Company $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'uid' => $model->uid]);
                 }
            ],
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="fas fa-plus"></i>', ['/company/create'], [
                        'title' => Yii::t('app', 'Add Company'),
                        'class' => 'btn btn-success'
                    ]) . ' ' .
                    Html::a('<i class="fas fa-redo"></i>', ['/company/index'], [
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
