<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\FlightProposal $model */

$this->title = Yii::t('app', 'Create Flight Proposal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Flight Proposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flight-proposal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
