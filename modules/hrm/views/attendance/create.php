<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Attendance $model */

$this->title = Yii::t('app', 'Create Attendance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attendances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attendance-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
