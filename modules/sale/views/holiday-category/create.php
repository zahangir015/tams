<?php

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\holiday\HolidayCategory */

$this->title = Yii::t('app', 'Create Holiday Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Holiday Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
