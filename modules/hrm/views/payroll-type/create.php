<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\PayrollType $model */

$this->title = Yii::t('app', 'Create Payroll Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payroll Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
