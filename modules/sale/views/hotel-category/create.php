<?php

/** @var yii\web\View $this */
/** @var \app\modules\sale\models\hotel\HotelCategory $model */

$this->title = Yii::t('app', 'Create Hotel Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotel Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-category-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
