<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Department $model */

$this->title = Yii::t('app', 'Create Department');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-create">
    <?= $this->render('_form', [
        'model' => $model,
        'departments' => $departments,
    ]) ?>

</div>
