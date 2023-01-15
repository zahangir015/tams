<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AirlineRepository $model */

$this->title = Yii::t('app', 'Create Airline Repository');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Airline Repositories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="airline-repository-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
