<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Invoice */

$this->title = Yii::t('app', 'Create Invoice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
