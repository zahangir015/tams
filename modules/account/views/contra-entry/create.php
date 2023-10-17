<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ContraEntry $model */

$this->title = Yii::t('app', 'Create Contra Entry');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contra Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contra-entry-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
