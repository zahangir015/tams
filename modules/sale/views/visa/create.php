<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\visa\Visa */

$this->title = Yii::t('app', 'Create Visa');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Visas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
