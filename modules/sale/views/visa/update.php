<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\Visa */

$this->title = Yii::t('app', 'Update Visa: {name}', [
    'name' => $model->identificationNumber,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Visas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identificationNumber, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="visa-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
