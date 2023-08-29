<?php

use app\components\GlobalConstant;
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
    <?php
    if (\app\modules\admin\components\Helper::checkRoute('/site/sales-report')) {
        ?>
        <div class="row">
            <div class="col-md">
                <div class="card card-widget widget-user shadow">
                    <h5 class="font-weight-bold text-center mt-3">Today's Sale</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box border border-primary text-primary">
                                    <div class="inner">
                                        <h3><?= $saleData['currentDaySales']['ticket']['total'] ?></h3> Flight
                                        <p>BDT <?= $saleData['currentDaySales']['ticket']['quoteAmount'] ?: 0 ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-plane-departure"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box border border-success text-success">
                                    <div class="inner">
                                        <h3><?= $saleData['currentDaySales']['hotel']['total'] ?></h3> Hotel
                                        <p>BDT <?= $saleData['currentDaySales']['hotel']['quoteAmount'] ?: 0 ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box border border-warning text-warning">
                                    <div class="inner">
                                        <h3><?= $saleData['currentDaySales']['holiday']['total'] ?></h3> Holiday
                                        <p>BDT <?= $saleData['currentDaySales']['holiday']['quoteAmount'] ?: 0 ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-suitcase-rolling"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box border border-danger text-danger">
                                    <div class="inner">
                                        <h3><?= $saleData['currentDaySales']['visa']['total'] ?></h3> Visa
                                        <p>BDT <?= $saleData['currentDaySales']['visa']['total'] ?: 0 ?></p>
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
                    <h5 class="font-weight-bold text-center mt-3">Monthly Sale</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box border border-primary text-primary">
                                    <div class="inner">
                                        <h3><?= $saleData['currentMonthSales']['ticket']['total'] ?></h3> Flight
                                        <p>BDT <?= $saleData['currentMonthSales']['ticket']['quoteAmount'] ?: 0 ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-plane-departure"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box border border-success text-success">
                                    <div class="inner">
                                        <h3><?= $saleData['currentMonthSales']['hotel']['total'] ?></h3> Hotel
                                        <p>BDT <?= $saleData['currentMonthSales']['hotel']['quoteAmount'] ?: 0 ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box border border-warning text-warning">
                                    <div class="inner">
                                        <h3><?= $saleData['currentMonthSales']['holiday']['total'] ?></h3> Holiday
                                        <p>BDT <?= $saleData['currentMonthSales']['holiday']['quoteAmount'] ?: 0 ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-suitcase-rolling"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box border border-danger text-danger">
                                    <div class="inner">
                                        <h3><?= $saleData['currentMonthSales']['visa']['total'] ?></h3> Visa
                                        <p>BDT <?= $saleData['currentMonthSales']['visa']['total'] ?: 0 ?></p>
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
                    <h5 class="font-weight-bold text-center mt-3">Payable/Receivable</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Today's Receivable</p>
                                        <h3><?= $receivable ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <p>Today's Payable</p>
                                        <h3><?= $payable ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-arrow-circle-down"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Monthly Receivable</p>
                                        <h3><?= $monthlyReceivable ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-arrow-alt-circle-up"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <p>Monthly Payable</p>
                                        <h3><?= $monthlyPayable ?></h3>
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
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card card-widget widget-user shadow">
                    <h5 class="font-weight-bold text-center mt-3">Top Sales</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="info-box bg-gradient-primary">
                                    <span class="info-box-icon"><i class="fas fa-plane-departure"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><?= $saleData['currentDaySales']['ticket']['quoteAmount'] ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $ticketPercentage ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                    <?= $ticketPercentage ?>% in Today's Flight sale
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-box bg-gradient-success">
                                    <span class="info-box-icon"><i class="fas fa-hotel"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><?= $saleData['currentDaySales']['hotel']['quoteAmount'] ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $hotelPercentage ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                    <?= $hotelPercentage ?>% in Today's Hotel sale
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-box bg-gradient-warning">
                                    <span class="info-box-icon"><i class="fas fa-suitcase-rolling"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><?= $saleData['currentDaySales']['holiday']['quoteAmount'] ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $holidayPercentage ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                    <?= $holidayPercentage ?>% in Today's Holiday sale
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-box bg-gradient-danger">
                                    <span class="info-box-icon"><i class="fas fa-passport"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><?= $saleData['currentDaySales']['visa']['quoteAmount'] ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $visaPercentage ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                    <?= $visaPercentage ?>% in Today's Visa sale
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card card-widget widget-user shadow">
                    <h5 class="font-weight-bold text-center mt-3">Top Monthly Sales</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="info-box bg-gradient-primary">
                                    <span class="info-box-icon"><i class="fas fa-plane-departure"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><?= $saleData['currentMonthSales']['ticket']['quoteAmount'] ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $ticketPercentage ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                    <?= $ticketPercentage ?>% in Monthly Flight Sales
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-box bg-gradient-success">
                                    <span class="info-box-icon"><i class="fas fa-hotel"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><?= $saleData['currentMonthSales']['hotel']['quoteAmount'] ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $hotelPercentage ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                    <?= $hotelPercentage ?>% in Monthly Hotel Sales
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-box bg-gradient-warning">
                                    <span class="info-box-icon"><i class="fas fa-suitcase-rolling"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><?= $saleData['currentMonthSales']['holiday']['quoteAmount'] ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $holidayPercentage ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                    <?= $holidayPercentage ?>% in Monthly Holiday Sales
                                </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-box bg-gradient-danger">
                                    <span class="info-box-icon"><i class="fas fa-passport"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number"><?= $saleData['currentMonthSales']['visa']['quoteAmount'] ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $visaPercentage ?>%"></div>
                                        </div>
                                        <span class="progress-description">
                                    <?= $visaPercentage ?>% in Monthly Visa Sales
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="col-md">
            <div class="card card-widget widget-user shadow">
                <h5 class="font-weight-bold text-center mt-3">Top Sale Source</h5>
                <div class="card-body">
                    <div class="col-12">
                        <div class="info-box bg-gradient-primary">
                            <span class="info-box-icon"><i class="fas fa-plane-departure"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?/*= $saleData['currentDaySales']['ticket']['quoteAmount'] */
            ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?/*= $ticketPercentage */
            ?>%"></div>
                                </div>
                                <span class="progress-description">
                                    <?/*= $ticketPercentage */
            ?>% in Today's sale
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fas fa-hotel"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?/*= $saleData['currentDaysales']{}['quoteAmount'] */
            ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?/*= $hotelPercentage */
            ?>%"></div>
                                </div>
                                <span class="progress-description">
                                    <?/*= $hotelPercentage */
            ?>% in Today's sale
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon"><i class="fas fa-suitcase-rolling"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?/*= $saleData['holidaySalesData']['currentDayData']['quoteAmount'] */
            ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?/*= $holidayPercentage */
            ?>%"></div>
                                </div>
                                <span class="progress-description">
                                    <?/*= $holidayPercentage */
            ?>% in Today's sale
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-box bg-gradient-danger">
                            <span class="info-box-icon"><i class="fas fa-passport"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?/*= $saleData['visaSalesData']['currentDayData']['quoteAmount'] */
            ?></span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?/*= $visaPercentage */
            ?>%"></div>
                                </div>
                                <span class="progress-description">
                                    <?/*= $visaPercentage */
            ?>% in Today's sale
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="card card-widget widget-user shadow bg-light-blue">
                    <h5 class="font-weight-bold text-center mt-3">Profit/Loos</h5>
                    <div class="card-body">
                        <div class="col-12">
                            <p><b>Today's</b> <?= $totalNetProfit ?></p>
                        </div>
                        <div class="col-12">
                            <p><b>Current Month</b> <?= $totalMonthlyNetProfit ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card card-widget widget-user shadow">
                    <h5 class="font-weight-bold text-center mt-3">Supplier Details</h5>
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
        <?php
    }
    if (\app\modules\admin\components\Helper::checkRoute('/site/attendance-report')) {
        ?>
        <div class="row">
            <div class="col-md-5">
                <div class="card card-widget widget-user shadow">
                    <h5 class="font-weight-bold text-center mt-3">Attendance Report</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Entry Time</p>
                                        <h3><?= $leaveAttendanceData['currentDayAttendanceData']['entry'] ?? 0 ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <p>Exit Time</p>
                                        <h3><?= $leaveAttendanceData['currentDayAttendanceData']['exit'] ?? 0 ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Total LateIn</p>
                                        <h3><?= $leaveAttendanceData['currentMonthAttendanceData']['totalLate'] ?? 0 ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fingerprint"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <p>Total Short Work</p>
                                        <h3><?= $leaveAttendanceData['currentMonthAttendanceData']['totalEarlyOut'] ?? 0 ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-minus-circle"></i>
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
                            <?php
                            foreach ($leaveAttendanceData['leaveAllocationData'] as $key => $singleData) {
                                ?>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="small-box <?= GlobalConstant::BG_COLOR_CLASS[$key] ?>">
                                        <div class="inner">
                                            <p><?= $singleData['leaveAllocationData']['leaveType']['name'] ?></p>
                                            <h3>Availed <?= $singleData['leaveAllocationData']['availedDays'] ?>
                                                of <?= $singleData['leaveAllocationData']['totalDays'] ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <!--<div class="col-lg-6 col-md-6 col-sm-12 col-12">
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
                            </div>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>