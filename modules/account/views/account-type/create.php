<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountType $model */

$this->title = Yii::t('app', 'Create Account Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
