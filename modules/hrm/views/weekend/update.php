<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Weekend $model */

$this->title = Yii::t('app', 'Update Weekend: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Weekends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="weekend-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
