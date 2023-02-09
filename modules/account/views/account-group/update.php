<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountGroup $model */

$this->title = Yii::t('app', 'Update Account Group: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="account-group-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
