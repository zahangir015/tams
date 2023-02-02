<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ExpenseSubCategory $model */

$this->title = Yii::t('app', 'Create Expense Sub Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expense Sub Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-sub-category-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
