<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\RoomType $model */

$this->title = Yii::t('app', 'Create Room Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Room Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="room-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
