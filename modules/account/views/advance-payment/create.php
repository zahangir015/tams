<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AdvancePayment $model */

$this->title = Yii::t('app', 'Create Advance Payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advance Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advance-payment-create">
    <?= $this->render('_form', [
        'model' => $model,
        'transaction' => $transaction,
        'bankList' => $bankList,
    ]) ?>
</div>
