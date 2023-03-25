<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\hrm\models\Payslip $model */

$this->title = Yii::t('app', 'Create Payslip');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payslips'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payslip-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
