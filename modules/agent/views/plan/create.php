<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\agent\models\Plan $model */

$this->title = Yii::t('app', 'Create Plan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
