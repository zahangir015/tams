<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Invoice */

$this->title = Yii::t('app', 'Pay Invoice: {invoiceNumber}', [
    'invoiceNumber' => $model->invoiceNumber,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->invoiceNumber, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Pay');
?>
<div class="invoice-create">
    <?= $this->render('_form_pay', [
        'model' => $model,
        'transaction' => $transaction,
        'refundList' => $refundList,
        'bankList' => $bankList
    ]) ?>
</div>
