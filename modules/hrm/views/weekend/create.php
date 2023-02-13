<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Weekend $model */

$this->title = Yii::t('app', 'Create Weekend');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Weekends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="weekend-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
