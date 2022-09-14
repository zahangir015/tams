<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\ServicePaymentTimeline */

$this->title = Yii::t('app', 'Create Service Payment Timeline');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Service Payment Timelines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-payment-timeline-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
