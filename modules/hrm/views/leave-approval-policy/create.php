<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApprovalPolicy $model */

$this->title = Yii::t('app', 'Create Leave Approval Policy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Approval Policies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-approval-policy-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
