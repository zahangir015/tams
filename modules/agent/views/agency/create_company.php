<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\agent\models\Agency $model */

$this->title = Yii::t('app', 'Create Company for : {company}', [
    'company' => $model->company,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agency-create">
    <?= $this->render('_form_company', [
        'model' => $model,
        'company' => $company
    ]) ?>
</div>
