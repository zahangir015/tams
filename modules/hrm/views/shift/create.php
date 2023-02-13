<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Shift $model */

$this->title = Yii::t('app', 'Create Shift');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shifts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shift-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
