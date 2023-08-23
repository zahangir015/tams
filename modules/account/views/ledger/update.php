<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Ledger */

$this->title = Yii::t('app', 'Update Ledger: {name}', [
    'name' => $model->reference,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ledgers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="ledger-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
