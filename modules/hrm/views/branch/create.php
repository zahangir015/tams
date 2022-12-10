<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Branch $model */

$this->title = Yii::t('app', 'Create Branch');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
