<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Roster $model */

$this->title = Yii::t('app', 'Create Roster');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rosters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roster-create">
    <?= $this->render('_form', [
        'model' => $model,
        'departmentList' => $departmentList,
    ]) ?>
</div>
