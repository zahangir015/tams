<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\Expense $model */

$this->title = Yii::t('app', 'Update Expense: {identificationNumber}', [
    'identificationNumber' => $model->identificationNumber,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identificationNumber, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="expense-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
