<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\VisaSupplier */

$this->title = Yii::t('app', 'Create Visa Supplier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Visa Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visa-supplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
