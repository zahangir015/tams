<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\SupplierCategory */

$this->title = Yii::t('app', 'Create Supplier Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Supplier Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
