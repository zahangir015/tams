<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ExpenseCategory $model */

$this->title = Yii::t('app', 'Create Expense Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expense Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
