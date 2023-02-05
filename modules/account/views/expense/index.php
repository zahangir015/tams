<?php

use app\modules\account\models\Expense;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\account\models\ExpenseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Expenses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'categoryId',
            'subCategoryId',
            'supplierId',
            'name',
            'accruingMonth',
            'timingOfExp',
            'notes:ntext',
            'status',
            'createdAt',
            'updatedAt',
            'createdBy',
            'updatedBy',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Expense $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
