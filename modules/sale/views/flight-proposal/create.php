<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\FlightProposal $model */

$this->title = Yii::t('app', 'Create Flight Proposal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Flight Proposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flight-proposal-create">
    <?= $this->render('_form', [
        'model' => $model,
        'itinerary' => $itinerary
    ]) ?>
</div>
