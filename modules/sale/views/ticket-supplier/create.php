<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\ticket\TicketSupplier $model */

$this->title = Yii::t('app', 'Create Ticket Supplier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-supplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
