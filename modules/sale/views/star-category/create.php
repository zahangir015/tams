<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\sale\models\StarCategory $model */

$this->title = Yii::t('app', 'Create Star Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Star Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="star-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
