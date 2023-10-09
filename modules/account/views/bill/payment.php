<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Bill */

$this->title = Yii::t('app', 'Pay Bill');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-pay">
    <?= $this->render('_form_pay', [
        'model' => $model,
        'transaction' => $transaction,
        'refundList' => $refundList,
        'bankList' => $bankList
    ]) ?>
</div>
