<?php

use app\modules\sale\models\hotel\HotelRefund;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\hotel\Hotel */

$this->title = Yii::t('app', 'Create Hotel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-create">
    <?= $this->render('_form_refund', [
        'model' => $model,
        'motherHotel' => $motherHotel,
        'hotelRefund' => $hotelRefund,
    ]) ?>
</div>
