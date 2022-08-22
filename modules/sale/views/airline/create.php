<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\Airline */

$this->title = Yii::t('app', 'Create Airline');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Airlines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="airline-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
