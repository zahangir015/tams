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
    <!--<div class="chart">
        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?php /*= SmallBox::widget([
                'title' => '150',
                'text' => 'Flight Ticket Sale',
                'icon' => 'fas fa-plane',
                'theme' => 'gradient-yellow'
            ]) */?>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?php /*= SmallBox::widget([
                'title' => '44',
                'text' => 'Holiday Sale',
                'icon' => 'fas fa-suitcase',
                'theme' => 'gradient-orange',
            ]) */?>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?php /*= SmallBox::widget([
                'title' => '44',
                'text' => 'Visa Sale',
                'icon' => 'fas fa-passport',
                'theme' => 'gradient-success',
            ]) */?>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?php /*= SmallBox::widget([
                'title' => '44',
                'text' => 'Hotel Sale',
                'icon' => 'fas fa-hotel',
                'theme' => 'gradient-primary',
            ]) */?>
        </div>
    </div>-->

    <div class="row">
        <div class="col-md-4">
            <div class="card card-widget widget-user shadow">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">Alexander Pierce</h3>
                    <h5 class="widget-user-desc">Founder &amp; CEO</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">3,200</h5>
                                <span class="description-text">SALES</span>
                            </div>

                        </div>

                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">13,000</h5>
                                <span class="description-text">FOLLOWERS</span>
                            </div>

                        </div>

                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">35</h5>
                                <span class="description-text">PRODUCTS</span>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-widget widget-user shadow">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">Alexander Pierce</h3>
                    <h5 class="widget-user-desc">Founder &amp; CEO</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">3,200</h5>
                                <span class="description-text">SALES</span>
                            </div>

                        </div>

                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">13,000</h5>
                                <span class="description-text">FOLLOWERS</span>
                            </div>

                        </div>

                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">35</h5>
                                <span class="description-text">PRODUCTS</span>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-widget widget-user shadow">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">Alexander Pierce</h3>
                    <h5 class="widget-user-desc">Founder &amp; CEO</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">3,200</h5>
                                <span class="description-text">SALES</span>
                            </div>

                        </div>

                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">13,000</h5>
                                <span class="description-text">FOLLOWERS</span>
                            </div>

                        </div>

                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">35</h5>
                                <span class="description-text">PRODUCTS</span>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>