<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\PublicHoliday $model */

$this->title = Yii::t('app', 'Create Public Holiday');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Public Holidays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="public-holiday-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
