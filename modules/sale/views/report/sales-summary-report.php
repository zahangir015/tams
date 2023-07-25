<?php

use app\components\Constant;
use app\modules\sales\components\HotelComponent;
use app\modules\sales\components\PackageComponent;
use app\modules\sales\components\VisaComponent;
use app\modules\sales\models\FlightAirport;
use app\modules\sales\components\TicketComponent;
use app\modules\sales\services\ReportService;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Sales Reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Sale Report'), 'url' => ['ticket-sales-report']];
$this->params['breadcrumbs'][] = $this->title;
$getReportType = [];
if (isset(Yii::$app->request->get()["reportType"])) {
    $getReportType = Yii::$app->request->get()["reportType"];
}
?>


<div class="card card-custom mb-5">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">
                Daily Summary
            </h3>
        </div>
        <div class="card-toolbar">
            <small class="pull-right">Date: <?= date('jS \of F'); ?></small>
        </div>
    </div>
    <div class="card-body">
        <div class="row table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Services</th>
                    <th>Gross Sales</th>
                    <th>Quote Amount</th>
                    <th>Net Revenue</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $grossTotal = 0;
                $quoteTotal = 0;
                $revenueTotal = 0;
                $gross = $quote = $revenue = 0;

                foreach ($dailyReportData as $key => $data) {
                    if ($key == 'Air Ticket') {
                        $gross = ReportService::grossCalculationForReport($data['dailyTicketDataList']);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $data['dailyTicketDataList'], $data['dailyTicketRefundDataList']);
                        $revenue = ReportService::netRevenueForReport($key, $data['dailyTicketDataList'], $data['dailyTicketRefundDataList']);
                    } elseif ($key == 'Holidays') {
                        $gross = (double)array_sum(array_column($data['dailyPackageDataList'], 'quoteAmount'));
                        $quote = PackageComponent::quoteAmountCalculationForReport(null, $data['dailyPackageDataList'], $data['dailyPackageRefundDataList']);
                        $revenue = PackageComponent::netRevenueForReport($key, $data['dailyPackageDataList'], $data['dailyPackageRefundDataList']);
                    } elseif ($key == 'Hotel') {
                        $gross = (double)array_sum(array_column($data['dailyHotelDataList'], 'quoteAmount'));
                        $quote = HotelComponent::quoteAmountCalculationForReport(null, $data['dailyHotelDataList'], $data['dailyHotelRefundDataList']);
                        $revenue = HotelComponent::netRevenueForReport($key, $data['dailyHotelDataList'], $data['dailyHotelRefundDataList']);
                    } elseif ($key == 'Visa') {
                        $gross = (double)array_sum(array_column($data['dailyVisaDataList'], 'quoteAmount'));
                        $quote = VisaComponent::quoteAmountCalculationForReport(null, $data['dailyVisaDataList'], $data['dailyVisaRefundDataList']);
                        $revenue = VisaComponent::netRevenueForReport($key, $data['dailyVisaDataList'], $data['dailyVisaRefundDataList']);
                    } elseif ($key == 'Insurance') {
                        foreach ($data['dailyInsuranceDataList'] as $single) {
                            $gross = (double)$single['price'];
                            $quote = (double)$single['price'];
                            $revenue = (double)$single['profit'];
                            ?>
                            <tr>
                                <td><?= $single['type'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($quote) ?></td>
                                <td><?= number_format($revenue) ?></td>
                            </tr>
                            <?php
                            $grossTotal += $gross;
                            $quoteTotal += $quote;
                            $revenueTotal += $revenue;
                        }
                    }
                    ?>
                    <?php
                    if ($key != 'Insurance') {
                        ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= number_format($gross) ?></td>
                            <td><?= number_format($quote) ?></td>
                            <td><?= number_format($revenue) ?></td>
                        </tr>
                        <?php
                        $grossTotal += $gross;
                        $quoteTotal += $quote;
                        $revenueTotal += $revenue;
                    }
                }
                ?>
                </tbody>
                <tfoot>
                <tr style="background-color: #ccc;">
                    <th>Total</th>
                    <th><?= number_format($grossTotal) ?></th>
                    <th><?= number_format($quoteTotal) ?></th>
                    <th><?= number_format($revenueTotal) ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card card-custom mb-5">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">
                monthly Summary
            </h3>
        </div>
        <div class="card-toolbar">
            <small class="pull-right">Date: <?= date('jS \of F', strtotime(date('Y-m-01'))) . ' to ' . date('jS \of F') ?></small>
        </div>
    </div>
    <div class="card-body">
        <div class="row table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Services</th>
                    <th>Gross Sales</th>
                    <th>Quote Amount</th>
                    <th>Net Revenue</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $grossTotal = 0;
                $quoteTotal = 0;
                $revenueTotal = 0;
                $gross = $quote = $revenue = 0;

                foreach ($monthlyReportData as $key => $data) {
                    if ($key == 'Air Ticket') {
                        $gross = ReportService::grossCalculationForReport($data['monthlyTicketDataList']);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $data['monthlyTicketDataList'], $data['monthlyTicketRefundDataList']);
                        $revenue = ReportService::netRevenueForReport($key, $data['monthlyTicketDataList'], $data['monthlyTicketRefundDataList']);
                    } elseif ($key == 'Holidays') {
                        $gross = (double)array_sum(array_column($data['monthlyPackageDataList'], 'quoteAmount'));
                        $quote = PackageComponent::quoteAmountCalculationForReport(null, $data['monthlyPackageDataList'], $data['monthlyPackageRefundDataList']);
                        $revenue = PackageComponent::netRevenueForReport($key, $data['monthlyPackageDataList'], $data['monthlyPackageRefundDataList']);
                    } elseif ($key == 'Hotel') {
                        $gross = (double)array_sum(array_column($data['monthlyHotelDataList'], 'quoteAmount'));
                        $quote = HotelComponent::quoteAmountCalculationForReport(null, $data['monthlyHotelDataList'], $data['monthlyHotelRefundDataList']);
                        $revenue = HotelComponent::netRevenueForReport($key, $data['monthlyHotelDataList'], $data['monthlyHotelRefundDataList']);
                    } elseif ($key == 'Visa') {
                        $gross = (double)array_sum(array_column($data['monthlyVisaDataList'], 'quoteAmount'));
                        $quote = VisaComponent::quoteAmountCalculationForReport(null, $data['monthlyVisaDataList'], $data['monthlyVisaRefundDataList']);
                        $revenue = VisaComponent::netRevenueForReport($key, $data['monthlyVisaDataList'], $data['monthlyVisaRefundDataList']);
                    } elseif ($key == 'Insurance') {
                        foreach ($data['monthlyInsuranceDataList'] as $single) {
                            $gross = (double)$single['price'];
                            $quote = (double)$single['price'];
                            $revenue = (double)$single['profit'];
                            ?>
                            <tr>
                                <td><?= $single['type'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($quote) ?></td>
                                <td><?= number_format($revenue) ?></td>
                            </tr>
                            <?php
                            $grossTotal += $gross;
                            $quoteTotal += $quote;
                            $revenueTotal += $revenue;
                        }
                    }
                    ?>
                    <?php
                    if ($key != 'Insurance') {
                        ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= number_format($gross) ?></td>
                            <td><?= number_format($quote) ?></td>
                            <td><?= number_format($revenue) ?></td>
                        </tr>
                        <?php
                        $grossTotal += $gross;
                        $quoteTotal += $quote;
                        $revenueTotal += $revenue;
                    }
                }
                ?>
                </tbody>
                <tfoot>
                <tr style="background-color: #ccc;">
                    <th>Total</th>
                    <th><?= number_format($grossTotal) ?></th>
                    <th><?= number_format($quoteTotal) ?></th>
                    <th><?= number_format($revenueTotal) ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card card-custom mb-5">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">
                Previous Month's Summary
            </h3>
        </div>
        <div class="card-toolbar">
            <small class="pull-right">Date: <?= date('jS \of F', strtotime(date("Y-m-01", strtotime("first day of previous month")))) . ' to ' . date('jS \of F', strtotime(date("Y-m-01", strtotime("last day of previous month")))) ?></small>
        </div>
    </div>
    <div class="card-body">
        <div class="row table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Services</th>
                    <th>Gross Sales</th>
                    <th>Quote Amount</th>
                    <th>Net Revenue</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $grossTotal = 0;
                $quoteTotal = 0;
                $revenueTotal = 0;
                $gross = $quote = $revenue = 0;

                foreach ($previousMonthsReportData as $key => $data) {
                    if ($key == 'Air Ticket') {
                        $gross = ReportService::grossCalculationForReport($data['previousMonthsTicketDataList']);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $data['previousMonthsTicketDataList'], $data['previousMonthsTicketRefundDataList']);
                        $revenue = ReportService::netRevenueForReport($key, $data['previousMonthsTicketDataList'], $data['previousMonthsTicketRefundDataList']);
                    } elseif ($key == 'Holidays') {
                        $gross = (double)array_sum(array_column($data['previousMonthsPackageDataList'], 'quoteAmount'));
                        $quote = PackageComponent::quoteAmountCalculationForReport(null, $data['previousMonthsPackageDataList'], $data['previousMonthsPackageRefundDataList']);
                        $revenue = PackageComponent::netRevenueForReport($key, $data['previousMonthsPackageDataList'], $data['previousMonthsPackageRefundDataList']);
                    } elseif ($key == 'Hotel') {
                        $gross = (double)array_sum(array_column($data['previousMonthsHotelDataList'], 'quoteAmount'));
                        $quote = HotelComponent::quoteAmountCalculationForReport(null, $data['previousMonthsHotelDataList'], $data['previousMonthsHotelRefundDataList']);
                        $revenue = HotelComponent::netRevenueForReport($key, $data['previousMonthsHotelDataList'], $data['previousMonthsHotelRefundDataList']);
                    } elseif ($key == 'Visa') {
                        $gross = (double)array_sum(array_column($data['previousMonthsVisaDataList'], 'quoteAmount'));
                        $quote = VisaComponent::quoteAmountCalculationForReport(null, $data['previousMonthsVisaDataList'], $data['previousMonthsVisaRefundDataList']);
                        $revenue = VisaComponent::netRevenueForReport($key, $data['previousMonthsVisaDataList'], $data['previousMonthsVisaRefundDataList']);
                    } elseif ($key == 'Insurance') {
                        foreach ($data['previousMonthsInsuranceDataList'] as $single) {
                            $gross = (double)$single['price'];
                            $quote = (double)$single['price'];
                            $revenue = (double)$single['profit'];
                            ?>
                            <tr>
                                <td><?= $single['type'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($quote) ?></td>
                                <td><?= number_format($revenue) ?></td>
                            </tr>
                            <?php
                            $grossTotal += $gross;
                            $quoteTotal += $quote;
                            $revenueTotal += $revenue;
                        }
                    }
                    ?>
                    <?php
                    if ($key != 'Insurance') {
                        ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= number_format($gross) ?></td>
                            <td><?= number_format($quote) ?></td>
                            <td><?= number_format($revenue) ?></td>
                        </tr>
                        <?php
                        $grossTotal += $gross;
                        $quoteTotal += $quote;
                        $revenueTotal += $revenue;
                    }
                }
                ?>
                </tbody>
                <tfoot>
                <tr style="background-color: #ccc;">
                    <th>Total</th>
                    <th><?= number_format($grossTotal) ?></th>
                    <th><?= number_format($quoteTotal) ?></th>
                    <th><?= number_format($revenueTotal) ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

