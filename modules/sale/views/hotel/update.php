<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\hotel\Hotel */

$this->title = Yii::t('app', 'Update Hotel: {name}', [
    'name' => $model->identificationNumber,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identificationNumber, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="hotel-update">
    <?= $this->render('_update', [
        'model' => $model,
    ]) ?>
</div>
