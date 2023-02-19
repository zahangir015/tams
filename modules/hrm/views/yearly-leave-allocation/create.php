<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\YearlyLeaveAllocation $model */

$this->title = Yii::t('app', 'Create Yearly Leave Allocation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Yearly Leave Allocations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yearly-leave-allocation-create">
    <?= $this->render('_form', [
        'model' => $model,
        'types' => $types
    ]) ?>
</div>
