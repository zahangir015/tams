<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Bill */

$this->title = Yii::t('app', 'Create Bill');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-create">
    <?= $this->render('_form', [
        'model' => $model,
        'transaction' => $transaction,
        'bankList' => $bankList
    ]) ?>
</div>
