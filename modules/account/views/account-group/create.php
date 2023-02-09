<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountGroup $model */

$this->title = Yii::t('app', 'Create Account Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-group-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
