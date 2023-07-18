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
    </div>-->

    <div class="row">
        <div class="col-md">
            <div class="card card-widget widget-user shadow bg-gray-light">
                <h5 class="font-weight-bold text-center mt-3">Today's Sale</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-light-green">
                                <div class="inner">
                                    <h3>150</h3> Flights
                                    <p>New Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-light-blue">
                                <div class="inner">
                                    <h3>150</h3> Hotels
                                    <p>New Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-light-yellow">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>New Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-light-red">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>New Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card card-widget widget-user shadow">
                <h5 class="font-weight-bold text-center mt-3">Payable/Receivable</h5>
                <div class="card-body">
                    <div class="col-12">
                        <div class="small-box bg-light-green">
                            <div class="inner">
                                <h3>150</h3>
                                <p>Total Receivable</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="small-box bg-light-red">
                            <div class="inner">
                                <h3>150</h3>
                                <p>Total Payable</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">

        </div>
    </div>


    <div class="row">
        <div class="col-md-4">
            <div class="card card-widget widget-user shadow bg-light-blue">
                <h5 class="font-weight-bold text-center mt-3">Profit/Loos</h5>
                <div class="card-body">
                    <div class="col-12">
                        <p><b>Today's</b>  </p>
                    </div>
                    <div class="col-12">
                        <p><b>Last Week</b></p>
                    </div>
                    <div class="col-12">
                        <p><b>Last Month</b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-widget widget-user shadow">
                <div class="widget-user-header bg-light-green">
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