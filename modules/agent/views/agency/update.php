<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\agent\models\Agency $model */

$this->title = Yii::t('app', 'Update Agency: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="agency-update">
    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>
</div>
