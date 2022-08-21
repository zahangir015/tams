<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\AirlineHistory */

$this->title = Yii::t('app', 'Create Airline History');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Airline Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="airline-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
