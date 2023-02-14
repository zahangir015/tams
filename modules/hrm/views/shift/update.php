<?php

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Shift $model */

$this->title = Yii::t('app', 'Update Shift: {title}', [
    'title' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="shift-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
