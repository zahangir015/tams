<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Employee $model */

$this->title = Yii::t('app', 'Update Employee: {name}', [
    'name' => $model->firstName.' '.$model->lastName,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="employee-update">

    <?= $this->render('_form', [
        'model' => $model,
        'designation' => $designation,
        'branchList' => $branchList,
        'departmentList' => $departmentList,
    ]) ?>

</div>
