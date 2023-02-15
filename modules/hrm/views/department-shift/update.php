<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\DepartmentShift $model */

$this->title = Yii::t('app', 'Update Department Shift: {name}', [
    'name' => $model->department->name.' - '.$model->shift->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Department Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->department->name.' - '.$model->shift->title, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="department-shift-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
