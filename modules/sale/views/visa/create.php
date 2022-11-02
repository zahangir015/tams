<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\Visa */

$this->title = Yii::t('app', 'Create Visa');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Visas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visa-create">
    <?= $this->render('_form', [
        'model' => $model,
        'visaSupplier' => $visaSupplier,
    ]) ?>
</div>
