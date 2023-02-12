<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Weekend $model */

$this->title = Yii::t('app', 'Create Weekend');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Weekends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weekend-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
