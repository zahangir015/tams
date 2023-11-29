<?php

use app\components\GlobalConstant;
use hail812\adminlte\widgets\Alert;
use hail812\adminlte\widgets\Callout;
use hail812\adminlte\widgets\InfoBox;
use hail812\adminlte\widgets\Ribbon;
use hail812\adminlte\widgets\SmallBox;
use yii\helpers\Url;

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
        <!-- Profit, loss, sales, due chart start -->
        <div class="mb-4 rounded common-bg pb-3 px-3">
            <div class="d-flex justify-content-center pt-3">
                <div class="d-flex align-items-center mr-2">
                    <div class="label-box bg-info"></div>
                    <p class="mb-0 text-dark ml-1">Sales</p>
                </div>
                <div class="d-flex align-items-center mr-2">
                    <div class="label-box bg-warning"></div>
                    <p class="mb-0 text-dark ml-1">Due</p>
                </div>
                <div class="d-flex align-items-center mr-2">
                    <div class="label-box bg-success"></div>
                    <p class="mb-0 text-dark ml-1">Profit</p>
                </div>
                <div class="d-flex align-items-center mr-2">
                    <div class="label-box bg-danger"></div>
                    <p class="mb-0 text-dark ml-1">Loss</p>
                </div>
            </div>
            <h4 class="text-dark text-center font-weight-normal mt-2">Sales Summary 2023</h4>
            <canvas height="100px" id="salesDueChart"></canvas>
        </div>
        <!-- Profit, loss, sales, due chart End -->

        <!-- Total recievable, payable start -->
        <div class="row mb-4">
            <div class="col-lg-4 mb-lg-0 mb-3">
                <div class="common-bg p-3 rounded todays-sales-container">
                    <h6 class="text-center text-dark">Today's Sales</h6>
                    <div class="row no-gutters">
                        <div class="col-6 p-1">
                            <div class="todays-sales border border-success rounded py-1 px-2 position-relative">
                                <p class="text-dark mb-0"><span
                                            class="text-success number"><?= $saleData['currentDaySales']['ticket']['total'] ?></span>
                                    Flights</p>
                                <p class="text-success mb-1 font-weight-bold"><?= $saleData['currentDaySales']['ticket']['quoteAmount'] ?: 0 ?>
                                    TK</p>
                                <div class="bg-success rounded p-1 icon-block d-flex align-items-center">
                                    <img class="w-100" src="<?= Url::to('/uploads/images/flights.svg') ?>" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 p-1">
                            <div class="todays-sales border border-danger rounded py-1 px-2 position-relative">
                                <p class="text-dark mb-0"><span
                                            class="text-danger number"><?= $saleData['currentDaySales']['holiday']['total'] ?></span>
                                    Holidays</p>
                                <p class="text-danger mb-1 font-weight-bold"><?= $saleData['currentDaySales']['holiday']['quoteAmount'] ?: 0 ?>
                                    TK</p>
                                <div class="bg-danger rounded p-1 icon-block d-flex align-items-center">
                                    <img class="w-100" src="<?= Url::to('/uploads/images/holidays.svg') ?>" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 p-1">
                            <div class="todays-sales border border-warning rounded py-1 px-2 position-relative">
                                <p class="text-dark mb-0"><span
                                            class="text-warning number"><?= $saleData['currentDaySales']['hotel']['total'] ?></span>
                                    Hotels</p>
                                <p class="text-warning mb-1 font-weight-bold"><?= $saleData['currentDaySales']['hotel']['quoteAmount'] ?: 0 ?>
                                    TK</p>
                                <div class="bg-warning rounded p-1 icon-block d-flex align-items-center">
                                    <img class="w-100" src="<?= Url::to('/uploads/images/hotels.svg') ?>" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 p-1">
                            <div class="todays-sales border border-info rounded py-1 px-2 position-relative">
                                <p class="text-dark mb-0"><span
                                            class="text-info number"><?= $saleData['currentDaySales']['visa']['total'] ?></span>
                                    Visas</p>
                                <p class="text-info mb-1 font-weight-bold"><?= $saleData['currentDaySales']['visa']['quoteAmount'] ?: 0 ?>
                                    TK</p>
                                <div class="bg-info rounded p-1 icon-block d-flex align-items-center">
                                    <img class="w-100" src="<?= Url::to('/uploads/images/visa.svg') ?>" alt=""/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-lg-0 mb-3">
                <div class="receivable p-4 rounded d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="text-dark">Total Receivable</h5>
                        <h3 class="text-success mb-1 font-weight-bold"><?= $receivable ?> TK</h3>
                    </div>
                    <div class="w-full d-flex justify-content-end">
                        <img src="<?= Url::to('/uploads/images/receivable.svg') ?>" class="w-25" alt=""/>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-lg-0 mb-3">
                <div class="payable p-4 rounded d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="text-dark">Total Payable</h5>
                        <h3 class="text-danger mb-1 font-weight-bold"><?= $payable ?> TK</h3>
                    </div>
                    <div class="w-full d-flex justify-content-end">
                        <img src="<?= Url::to('/uploads/images/payable.svg') ?>" class="w-25" alt=""/>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total recievable, payable end -->

        <!-- Profit/loss, supplier lists start  -->
        <div class="row mb-4">
            <div class="col-lg-3 mb-lg-0 mb-3">
                <div class="bg-primary rounded p-2 profit-loss-supplier">
                    <h6 class="text-white font-weight-normal text-center my-3">Profit/Loss</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="text-white font-weight-normal">Today</h6>
                        <h6 class="text-white"><?= number_format($totalNetProfit, 2) ?></h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="text-white font-weight-normal">Last Week</h6>
                        <h6 class="text-white"><?= number_format($totalMonthlyNetProfit, 2) ?></h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="text-white font-weight-normal">Last Month</h6>
                        <h6 class="text-white"><?= number_format($totalPreviousMonthlyNetProfit, 2) ?></h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 mb-lg-0 mb-3">
                <div class="common-bg p-3 rounded profit-loss-supplier">
                    <h5 class="text-center text-dark mb-3">Supplier List</h5>
                    <div class="row">
                        <!-- single supplier start -->
                        <div class="col-lg-4 mb-lg-0 mb-3">
                            <div class="supplier rounded px-2 py-3">
                                <h5 class="text-center text-dark mb-0">01 Raihan Pathan</h5>
                                <p class="text-muted text-center mb-0">Payment Date Fortnight</p>
                                <hr class="m-0 w-75 mx-auto mt-2 bg-dark supplier-divider"/>
                                <div class="row no-gutters mt-2">
                                    <div class="col-6">
                                        <h6 class="text-center text-dark mb-0">Limit</h6>
                                        <h6 class="text-center text-success mb-0">15,00,000</h6>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-center text-dark mb-0">Used</h6>
                                        <h6 class="text-center text-danger mb-0">15,00,000</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- single supplier end -->
                        <!-- single supplier start -->
                        <div class="col-lg-4 mb-lg-0 mb-3">
                            <div class="supplier rounded px-2 py-3">
                                <h5 class="text-center text-dark mb-0">01 Raihan Pathan</h5>
                                <p class="text-muted text-center mb-0">Payment Date Fortnight</p>
                                <hr class="m-0 w-75 mx-auto mt-2 bg-dark supplier-divider"/>
                                <div class="row no-gutters mt-2">
                                    <div class="col-6">
                                        <h6 class="text-center text-dark mb-0">Limit</h6>
                                        <h6 class="text-center text-success mb-0">15,00,000</h6>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-center text-dark mb-0">Used</h6>
                                        <h6 class="text-center text-danger mb-0">15,00,000</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- single supplier end -->
                        <!-- single supplier start -->
                        <div class="col-lg-4 mb-lg-0 mb-3">
                            <div class="supplier rounded px-2 py-3">
                                <h5 class="text-center text-dark mb-0">01 Raihan Pathan</h5>
                                <p class="text-muted text-center mb-0">Payment Date Fortnight</p>
                                <hr class="m-0 w-75 mx-auto mt-2 bg-dark supplier-divider"/>
                                <div class="row no-gutters mt-2">
                                    <div class="col-6">
                                        <h6 class="text-center text-dark mb-0">Limit</h6>
                                        <h6 class="text-center text-success mb-0">15,00,000</h6>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-center text-dark mb-0">Used</h6>
                                        <h6 class="text-center text-danger mb-0">15,00,000</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- single supplier end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Profit/loss, supplier lists end  -->

        <!-- Attendance and leave start  -->
        <div class="row mb-4">
            <div class="col-lg-4 mb-lg-0 mb-3">
                <div class="common-bg p-2 rounded attendence-leave">
                    <h5 class="text-center text-dark mb-3">Attendence</h5>
                    <div class="row no-gutters">
                        <!-- entry time start  -->
                        <div class="col-6 p-2">
                            <div class="entry-time rounded p-2">
                                <div class="attendence-icon rounded-pill bg-white d-flex align-items-center justify-content-center mx-auto">
                                    <img width="20px" src="<?= Url::to('/uploads/images/entry-time-icon.svg') ?>"
                                         alt=""/>
                                </div>
                                <p class="text-white text-center mb-0 mt-2">Entry Time</p>
                                <p class="text-white text-center mb-0"><?= $leaveAttendanceData['currentDayAttendanceData']['entry'] ?? 0 ?></p>
                            </div>
                        </div>
                        <!-- entry time End  -->
                        <!-- exit time start  -->
                        <div class="col-6 p-2">
                            <div class="exit-time rounded p-2">
                                <div class="attendence-icon rounded-pill bg-white d-flex align-items-center justify-content-center mx-auto">
                                    <img width="20px" src="<?= Url::to('/uploads/images/exit-time-icon.svg') ?>"
                                         alt=""/>
                                </div>
                                <p class="text-white text-center mb-0 mt-2">Exit Time</p>
                                <p class="text-white text-center mb-0"><?= $leaveAttendanceData['currentDayAttendanceData']['exit'] ?? 0 ?></p>
                            </div>
                        </div>
                        <!-- exit time end  -->
                        <!-- short work start  -->
                        <div class="col-6 p-2">
                            <div class="short-work rounded p-2">
                                <div class="attendence-icon rounded-pill bg-white d-flex align-items-center justify-content-center mx-auto">
                                    <img width="20px" src="<?= Url::to('/uploads/images/short-work-icon.svg') ?>"
                                         alt=""/>
                                </div>
                                <p class="text-white text-center mb-0 mt-2">Short Work</p>
                                <p class="text-white text-center mb-0"><?= $leaveAttendanceData['currentMonthAttendanceData']['totalEarlyOut'] ?? 0 ?></p>
                            </div>
                        </div>
                        <!-- short work end  -->
                        <!-- late in start  -->
                        <div class="col-6 p-2">
                            <div class="late-in rounded p-2">
                                <div class="attendence-icon rounded-pill bg-white d-flex align-items-center justify-content-center mx-auto">
                                    <img width="20px" src="<?= Url::to('/uploads/images/late-in-icon.svg') ?>" alt=""/>
                                </div>
                                <p class="text-white text-center mb-0 mt-2">Late In</p>
                                <p class="text-white text-center mb-0"><?= $leaveAttendanceData['currentMonthAttendanceData']['totalLate'] ?? 0 ?></p>
                            </div>
                        </div>
                        <!-- late in end  -->
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="common-bg rounded p-3 attendence-leave">
                    <h5 class="text-center text-dark mb-3">Leave Statistic</h5>
                    <div class="row no-gutters">
                        <?php
                        foreach ($leaveAttendanceData['leaveAllocationData'] as $key => $singleData) {
                            ?>
                            <div class="col-md-6 p-2 mb-lg-0 mb-3">
                                <div class="leave-stat-bg position-relative pt-4 px-3 rounded">
                                    <img class="leave-icon-position"
                                         src="<?= Url::to('/uploads/images/sick-leave-icon.svg') ?>" width="30px"
                                         alt=""/>
                                    <h5 class="text-center text-<?= GlobalConstant::BG_COLOR_CLASS[$key] ?>"><?= $singleData['leaveAllocationData']['leaveType']['name'] ?></h5>
                                    <p class="text-center text-dark">
                                        Availed <?= $singleData['leaveAllocationData']['availedDays'] ?> Out
                                        Of <?= $singleData['leaveAllocationData']['totalDays'] ?></p>
                                    <hr class="bottom-bar bg-<?= GlobalConstant::BG_COLOR_CLASS[$key] ?>"/>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Attendance and leave end  -->

        <!-- top sales source, sales, supplier start  -->
        <div class="row mb-4">
            <!-- top sale source start -->
            <div class="col-lg-4 col-md-6 mb-lg-0 mb-3">
                <div class="common-bg p-3 rounded top-sales-supplier">
                    <h5 class="text-center text-dark mb-3">Top Sales Source</h5>
                    <div class="row no-gutters align-items-center">
                        <div class="col-6">
                            <div>
                                <h5 class="text-success mb-0">40%</h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-success"></div>
                                    <p class="mb-0 text-dark ml-1">Existing Clients</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="text-danger mb-0">40%</h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-danger"></div>
                                    <p class="mb-0 text-dark ml-1">Online Clients</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="text-primary mb-0">20%</h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-primary"></div>
                                    <p class="mb-0 text-dark ml-1">Online Clients</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <canvas id="salesSourceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- top sale source end -->
            <!-- top sales start -->
            <div class="col-lg-4 col-md-6 mb-lg-0 mb-3">
                <div class="common-bg p-3 rounded top-sales-supplier">
                    <h5 class="text-center text-dark mb-3">Top Sales</h5>
                    <div class="row no-gutters align-items-center">
                        <div class="col-6">
                            <div>
                                <h5 class="text-success mb-0"><?= $ticketPercentage . '%' ?></h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-success"></div>
                                    <p class="mb-0 text-dark ml-1">Air Ticket</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="text-danger mb-0"><?= $hotelPercentage . '%' ?></h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-danger"></div>
                                    <p class="mb-0 text-dark ml-1">Hotel</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="text-warning mb-0"><?= $visaPercentage . '%' ?></h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-warning"></div>
                                    <p class="mb-0 text-dark ml-1">Visa</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="text-primary mb-0"><?= $holidayPercentage . '%' ?></h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-primary"></div>
                                    <p class="mb-0 text-dark ml-1">Holiday</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- top sales end -->
            <!-- top supplier start -->
            <div class="col-lg-4 col-md-6 mb-lg-0 mb-3">
                <div class="common-bg p-3 rounded top-sales-supplier">
                    <h5 class="text-center text-dark mb-3">Top Suppliers</h5>
                    <div class="row no-gutters align-items-center">
                        <div class="col-6">
                            <div>
                                <h5 class="text-success mb-0">40%</h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-success"></div>
                                    <p class="mb-0 text-dark ml-1">Chologhuri</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="text-danger mb-0">30%</h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-danger"></div>
                                    <p class="mb-0 text-dark ml-1">Take Off</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="text-warning mb-0">10%</h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-warning"></div>
                                    <p class="mb-0 text-dark ml-1">Khun Habibi</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="text-primary mb-0">20%</h5>
                                <div class="d-flex align-items-center mr-2">
                                    <div class="label-box bg-primary"></div>
                                    <p class="mb-0 text-dark ml-1">Esla Holidays</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <canvas id="supplierChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- top supplier end -->
        </div>
        <!-- top sales source, sales, supplier end  -->
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
                                        <p>BDT <?= $saleData['currentDaySales']['visa']['quoteAmount'] ?: 0 ?></p>
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
                                        <p>BDT <?= $saleData['currentMonthSales']['visa']['quoteAmount'] ?: 0 ?></p>
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
                                        <h5><?= $receivable ?></h5>
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
                                        <h5><?= $payable ?></h5>
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
                                        <h5><?= $monthlyReceivable ?></h5>
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
                                        <h5><?= $monthlyPayable ?></h5>
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