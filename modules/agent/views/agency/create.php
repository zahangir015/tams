<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\agent\models\Agency $model */

$this->title = Yii::t('app', 'Create Agency');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
