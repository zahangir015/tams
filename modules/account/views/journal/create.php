<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\account\models\Journal $model */

$this->title = Yii::t('app', 'Create Journal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Journals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="journal-create">

    <?= $this->render('_form', [
        'model' => $model,
        'journalEntry' => $journalEntry,
    ]) ?>

</div>
