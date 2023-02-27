<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Ticket */

$this->title = Yii::t('app', 'Update Refund Ticket');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Refund Tickets'), 'url' => ['refund-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-create">
    <?= $this->render('_form_refund', [
        'model' => $model,
        'ticketSupplier' => $model->ticketSupplier,
        'ticketRefund' => $model->ticketRefund,
    ]) ?>
</div>
