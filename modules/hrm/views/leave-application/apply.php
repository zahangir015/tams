<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApplication $model */

$this->title = Yii::t('app', 'Apply Leave');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Applications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-application-create">
    <?= $this->render('_form_apply', [
        'model' => $model,
    ]) ?>
</div>
