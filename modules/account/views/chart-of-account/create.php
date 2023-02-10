<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\ChartOfAccount $model */

$this->title = Yii::t('app', 'Create Chart Of Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Chart Of Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chart-of-account-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
