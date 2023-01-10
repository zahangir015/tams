<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Employee $model */

$this->title = Yii::t('app', 'Create Employee Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">
    <?= $this->render('_form', [
        'model' => $model,
        'designation' => $designation,
        'branchList' => $branchList,
        'departmentList' => $departmentList,
        'signup' => $signup,
    ]) ?>

</div>
