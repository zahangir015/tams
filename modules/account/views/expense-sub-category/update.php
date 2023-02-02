<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ExpenseSubCategory $model */

$this->title = Yii::t('app', 'Update Expense Sub Category: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expense Sub Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="expense-sub-category-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
