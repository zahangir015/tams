<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveType $model */

$this->title = Yii::t('app', 'Update Leave Type: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="leave-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
