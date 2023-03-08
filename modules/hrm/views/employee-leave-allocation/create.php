<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveAllocation $model */

$this->title = Yii::t('app', 'Create Employee Leave Allocation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Leave Allocations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-leave-allocation-create">
    <?= $this->render('_form', [
        'model' => $model,
        'types' => $types,
    ]) ?>
</div>
