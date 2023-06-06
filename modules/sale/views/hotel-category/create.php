<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\HotelCategory $model */

$this->title = Yii::t('app', 'Create Hotel Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotel Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
