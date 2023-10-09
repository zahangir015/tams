<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Bill */

$this->title = Yii::t('app', 'Update Bill: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bill-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
