<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\agent\models\AgencyAccountRequest $model */

$this->title = Yii::t('app', 'Create Agency Account Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agency Account Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-account-request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
