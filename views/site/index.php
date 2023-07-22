<?php

use hail812\adminlte\widgets\Alert;
use hail812\adminlte\widgets\Callout;
use hail812\adminlte\widgets\InfoBox;
use hail812\adminlte\widgets\Ribbon;
use hail812\adminlte\widgets\SmallBox;

$this->title = 'Dashboard';
$this->params['breadcrumbs'] = [['label' => $this->title]];
//dd($saleData);
?>
<div class="container-fluid">
    <!--<div class="chart">
        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
    </div>-->

    <div class="row">
        <div class="col-md">
            <div class="card card-widget widget-user shadow">
                <h5 class="font-weight-bold text-center mt-3">Today's Sale</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box border border-primary text-primary">
                                <div class="inner">
                                    <h3><?= $saleData['ticketSalesData']['total'] ?></h3> Flight
                                    <p>BDT <?= $saleData['ticketSalesData']['quoteAmount'] ?: 0 ?></p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-plane-departure"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box border border-success text-success">
                                <div class="inner">
                                    <h3><?= $saleData['hotelSalesData']['total'] ?></h3> Hotel
                                    <p>BDT <?= $saleData['hotelSalesData']['quoteAmount'] ?: 0 ?></p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hotel"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box border border-warning text-warning">
                                <div class="inner">
                                    <h3><?= $saleData['holidaySalesData']['total'] ?></h3> Holiday
                                    <p>BDT <?= $saleData['holidaySalesData']['quoteAmount'] ?: 0 ?></p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-suitcase-rolling"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box border border-danger text-danger">
                                <div class="inner">
                                    <h3><?= $saleData['visaSalesData']['total'] ?></h3> Visa
                                    <p>BDT <?= $saleData['visaSalesData']['quoteAmount'] ?: 0 ?></p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-passport"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card card-widget widget-user shadow">
                <h5 class="font-weight-bold text-center mt-3">Top Sales</h5>
                <div class="card-body">
                    <div class="col-12">
                        <div class="info-box bg-gradient-primary">
                            <span class="info-box-icon"><i class="fas fa-plane-departure"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?= ($saleData['ticketSalesData']['quoteAmount'] * 100)/array_sum(array_column($saleData, 'quoteAmount')) ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <!--<span class="progress-description">
                                    70% Increase in 30 Days
                                </span>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fas fa-hotel"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number">41,410</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <!--<span class="progress-description">
                                    70% Increase in 30 Days
                                </span>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon"><i class="fas fa-suitcase-rolling"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number">41,410</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <!--<span class="progress-description">
                                    70% Increase in 30 Days
                                </span>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box bg-gradient-danger">
                            <span class="info-box-icon"><i class="fas fa-passport"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?= $saleData['ticketSalesData']['quoteAmount'] * array_sum($saleData) ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 70%"></div>
                                </div>
                                <!--<span class="progress-description">
                                    70% Increase in 30 Days
                                </span>-->
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
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>150</h3>
                                <p>Total Receivable</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-arrow-alt-circle-up"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>150</h3>
                                <p>Total Payable</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-arrow-circle-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-5">
            <div class="card card-widget widget-user shadow bg-light-blue">
                <h5 class="font-weight-bold text-center mt-3">Profit/Loos</h5>
                <div class="card-body">
                    <div class="col-12">
                        <p><b>Today's</b></p>
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
        <div class="col-md-7">
            <div class="card card-widget widget-user shadow">
                <h5 class="font-weight-bold text-center mt-3">Profit/Loos</h5>
                <div class="card-footer pt-30">
                    <div class="row">
                        <div class="col-sm-3 col-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-success"><i
                                            class="fas fa-caret-up"></i> 17%</span>
                                <h5 class="description-header">$35,210.43</h5>
                                <span class="description-text">TOTAL REVENUE</span>
                            </div>
                        </div>

                        <div class="col-sm-3 col-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-warning"><i
                                            class="fas fa-caret-left"></i> 0%</span>
                                <h5 class="description-header">$10,390.90</h5>
                                <span class="description-text">TOTAL COST</span>
                            </div>
                        </div>

                        <div class="col-sm-3 col-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-success"><i
                                            class="fas fa-caret-up"></i> 20%</span>
                                <h5 class="description-header">$24,813.53</h5>
                                <span class="description-text">TOTAL PROFIT</span>
                            </div>
                        </div>

                        <div class="col-sm-3 col-6">
                            <div class="description-block">
                                <span class="description-percentage text-danger"><i
                                            class="fas fa-caret-down"></i> 18%</span>
                                <h5 class="description-header">1200</h5>
                                <span class="description-text">GOAL COMPLETIONS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="card card-widget widget-user shadow">
                <h5 class="font-weight-bold text-center mt-3">Attendance Report</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>Total Receivable</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>Total Payable</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>Total Receivable</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-danger">
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
        </div>
        <div class="col-md-7">
            <div class="card card-widget widget-user shadow">
                <h5 class="font-weight-bold text-center mt-3">Leave Report</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>Total Receivable</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>Total Payable</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>150</h3>
                                    <p>Total Receivable</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="small-box bg-danger">
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
        </div>
    </div>
</div>