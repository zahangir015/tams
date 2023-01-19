<?php

use hail812\adminlte\widgets\Alert;
use hail812\adminlte\widgets\Callout;
use hail812\adminlte\widgets\InfoBox;
use hail812\adminlte\widgets\Ribbon;
use hail812\adminlte\widgets\SmallBox;

$this->title = 'Dashboard';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?= SmallBox::widget([
                'title' => '150',
                'text' => 'Flight Ticket Sale',
                'icon' => 'fas fa-plane',
                'theme' => 'gradient-yellow'
            ]) ?>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?= SmallBox::widget([
                'title' => '44',
                'text' => 'Holiday Sale',
                'icon' => 'fas fa-suitcase',
                'theme' => 'gradient-orange',
            ]) ?>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?= SmallBox::widget([
                'title' => '44',
                'text' => 'Visa Sale',
                'icon' => 'fas fa-passport',
                'theme' => 'gradient-success',
            ]) ?>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?= SmallBox::widget([
                'title' => '44',
                'text' => 'Hotel Sale',
                'icon' => 'fas fa-hotel',
                'theme' => 'gradient-primary',
            ]) ?>
        </div>
    </div>
</div>