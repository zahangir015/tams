<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Provider */

$this->title = Yii::t('app', 'Create Provider');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Providers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
