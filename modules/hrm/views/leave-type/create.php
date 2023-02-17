<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveType $model */

$this->title = Yii::t('app', 'Create Leave Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
