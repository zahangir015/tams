<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\BankAccount */

$this->title = Yii::t('app', 'Create Bank Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bank Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-account-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
