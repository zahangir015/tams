<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\EmployeeLeaveAllocation $model */

$this->title = Yii::t('app', 'Create Employee Leave Allocation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Leave Allocations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-leave-allocation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
