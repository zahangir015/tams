<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ContraEntry $model */

$this->title = Yii::t('app', 'Update Contra Entry: {name}', [
    'name' => $model->identificationNumber,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contra Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identificationNumber, 'url' => ['view', 'uid' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="contra-entry-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
