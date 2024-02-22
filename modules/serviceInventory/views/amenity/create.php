<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\serviceInventory\models\Amenity $model */

$this->title = Yii::t('app', 'Create Amenity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Amenities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amenity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
