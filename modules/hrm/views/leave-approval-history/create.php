<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\LeaveApprovalHistory $model */

$this->title = Yii::t('app', 'Create Leave Approval History');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Approval Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-approval-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
