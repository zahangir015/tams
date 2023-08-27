<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    $usernameField,
];
if (!empty($extraColumns)) {
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons' => [
        'view' => function ($url, $model, $key) {
            return Html::a('<span class="fa fa-eye"></span>', [isset(Yii::$app->user->identity->agencyId) ? 'assign-route' : 'view', 'id' => $model->id], [
                'title' => Yii::t('app', 'Details'),
                'class' => 'btn btn-default btn-xs custom_button',
                'data-pjax' => '0',
            ]);
        },
    ],
];

?>
<div class="assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
