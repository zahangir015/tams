<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\HotelProposal $model */

$this->title = Yii::t('app', 'Create Hotel Proposal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotel Proposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-proposal-create">
    <?= $this->render('_form', [
        'model' => $model,
        'roomDetail' => $roomDetail,
    ]) ?>

</div>
