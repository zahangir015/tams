<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\DepartmentShift $model */

$this->title = Yii::t('app', 'Create Department Shift');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Department Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-shift-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
