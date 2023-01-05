<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\RefundTransaction $model */

$this->title = Yii::t('app', 'Create Refund Transaction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Refund Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="refund-transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'transaction' => $transaction,
    ]) ?>

</div>
