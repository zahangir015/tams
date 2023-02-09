<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountType $model */

$this->title = Yii::t('app', 'Update Account Type: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="account-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
