<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Ticket */

$this->title = Yii::t('app', 'Upload Ticket');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-upload">
    <?= $this->render('_form_upload', [
        'model' => $model,
        'ticketSupplier' => $ticketSupplier,
    ]) ?>
</div>
