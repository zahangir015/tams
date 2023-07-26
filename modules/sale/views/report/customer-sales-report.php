<?php

use app\components\Constant;
use app\modules\employee\models\Employee;
use app\modules\sales\models\Airline;
use app\modules\sales\models\FlightAirport;
use app\modules\sales\components\TicketComponent;
use app\modules\sales\services\ReportService;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Ticket Reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$getReportType = [];
if (isset(Yii::$app->request->get()["reportType"])) {
    $getReportType = Yii::$app->request->get()["reportType"];
}
?>

<div class="tickets-form">
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Filter Report
                </h3>
            </div>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(['method' => 'GET']); ?>
            <div class="row">
                <div class="col-md">
                    <?php
                    echo '<label class="control-label">Date Range</label>';
                    echo DateRangePicker::widget([
                        'name' => 'dateRange',
                        'value' => date('Y-m-d') . ' - ' . date('Y-m-d'),
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => ' - ',
                            ],
                            'opens' => 'left'
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md">
                    <?
                    ?>
                </div>
                <div class="col-md" style="padding-top:24px; padding-right:0">
                    <?= Html::submitButton(Yii::t('app', 'Generate Report'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php if (in_array("AIR_TICKET", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Air Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th>Report Terms</th>
                        <?php
                        foreach ($monthWiseTicketData as $key => $value) {
                            echo "<th>" . date('F Y', strtotime($key)) . "</th>";
                        }
                        ?>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <th>Gross Before Discount</th>
                        <?php
                        $grossTotal = $grossSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $grossSum = ReportService::grossCalculationForReport($value);
                            echo "<th>" . number_format($grossSum, 2) . "</th>";
                            $grossTotal += $grossSum;
                        }
                        ?>
                        <th><?= number_format($grossTotal, 2) ?></th>
                    </tr>
                    <tr>
                        <th>Gross Before Discount(AFTER REFUND ADJUSTMENT)</th>
                        <?php
                        $refundAdjustedGrossTotal = $refundAdjustedGrossSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $refundAdjustedGrossSum = ReportService::grossAfterRefundCalculationForReport($key, $value, $monthWiseTicketRefundData);
                            echo "<th>" . number_format($refundAdjustedGrossSum, 2) . "</th>";
                            $refundAdjustedGrossTotal += $refundAdjustedGrossSum;
                        }
                        ?>
                        <th><?= number_format($refundAdjustedGrossTotal, 2) ?></th>
                    </tr>
                    <tr>
                        <th>Total Quote</th>
                        <?php
                        $quoteTotal = $quoteSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $quote = ReportService::quoteAmountCalculationForReport($key, $value, $monthWiseTicketRefundData);
                            echo "<th>" . number_format($quote, 2) . "</th>";
                            $quoteTotal += $quote;
                        }
                        ?>
                        <th><?= number_format($quoteTotal, 2) ?></th>
                    </tr>
                    <tr>
                        <th colspan="2"><u>COST TO SHARETRIP</u></th>
                    </tr>
                    <tr>
                        <td>Ticket Base Fare</td>
                        <?php
                        $baseFareTotal = $baseFareSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $baseFareSum = ReportService::baseFareCalculationForReport($value);
                            echo "<td>" . number_format($baseFareSum, 2) . "</td>";
                            $baseFareTotal += $baseFareSum;
                        }
                        ?>
                        <td><?= number_format($baseFareTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <?php
                        $taxTotal = $taxSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $taxSum = ReportService::taxCalculationForReport($value);
                            echo "<td>" . number_format($taxSum, 2) . "</td>";
                            $taxTotal += $taxSum;
                        }
                        ?>
                        <td><?= number_format($taxTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Other Tax</td>
                        <?php
                        $otherTaxTotal = $otherTaxSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $otherTaxSum = ReportService::otherTaxCalculationForReport($value);
                            echo "<td>" . number_format($otherTaxSum, 2) . "</td>";
                            $otherTaxTotal += $otherTaxSum;
                        }
                        ?>
                        <td><?= number_format($otherTaxTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Supplier Service Charge</td>
                        <?php
                        $serviceChargeTotal = $serviceChargeSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $serviceChargeSum = ReportService::supplierServiceChargeCalculationForReport($value);
                            echo "<td>" . number_format($serviceChargeSum, 2) . "</td>";
                            $serviceChargeTotal += $serviceChargeSum;
                        }
                        ?>
                        <td><?= number_format($serviceChargeTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Advance Income Tax (AIT)</td>
                        <?php
                        $aitTotal = $aitSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $aitSum = ReportService::aitCalculationForReport($value);
                            echo "<td>" . number_format($aitSum, 2) . "</td>";
                            $aitTotal += $aitSum;
                        }
                        ?>
                        <td><?= number_format($aitTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Refund Cost Of Sale</td>
                        <?php
                        $refundCostTotal = $refundCostSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $refundCostSum = (double)array_sum(array_column($monthWiseTicketRefundData, 'costOfSale'));
                            echo "<td>" . number_format($refundCostSum, 2) . "</td>";
                            $refundCostTotal += $refundCostSum;
                        }
                        ?>
                        <td><?= number_format($refundCostTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Ticket Cost to Share Trip</th>
                        <?php
                        $costTotal = $costSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $costSum = ReportService::ticketCostCalculationForReport($key, $value, $monthWiseTicketRefundData);
                            echo "<th>" . number_format($costSum, 2) . "</th>";
                            $costTotal += $costSum;
                        }
                        ?>
                        <th><?= number_format($costTotal, 2) ?></th>
                    </tr>
                    <tr>
                        <td>Commissions</td>
                        <?php
                        $commissionTotal = $commissionSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $commissionSum = ReportService::commissionReceivedCalculationForReport($value);
                            echo "<td>" . number_format($commissionSum, 2) . "</td>";
                            $commissionTotal += $commissionSum;
                        }
                        ?>
                        <td><?= number_format($commissionTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Incentives</td>
                        <?php
                        $incentiveTotal = $incentiveSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $incentiveSum = ReportService::incentiveReceivedCalculationForReport($value);
                            echo "<td>" . number_format($incentiveSum, 2) . "</td>";
                            $incentiveTotal += $incentiveSum;
                        }
                        ?>
                        <td><?= number_format($incentiveTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <th>Net Cost to Share Trip</th>
                        <?php
                        $netCostTotal = $netCostSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $netCostSum = ReportService::netCostCalculationForReport($key, $value, $monthWiseTicketRefundData);
                            echo "<th>" . number_format($netCostSum, 2) . "</th>";
                            $netCostTotal += $netCostSum;
                        }
                        ?>
                        <th><?= number_format($netCostTotal, 2) ?></th>
                    </tr>
                    <tr>
                        <th colspan="2"><u>REVENUE</u></th>
                    </tr>
                    <tr>
                        <th>Net Revenue</th>
                        <?php
                        $revenueTotal = $revenueSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            /*$quoteAmountTotalSum = ReportService::quoteAmountCalculationForReport($key, $value, $monthWiseTicketRefundData);
                            $netCostSum = ReportService::netCostCalculationForReport($key, $value, $monthWiseTicketRefundData);
                            $revenueSum = ($quoteAmountTotalSum - $netCostSum);*/
                            $revenueSum = ReportService::netRevenueForReport($key, $value, $monthWiseTicketRefundData);
                            echo "<th>" . number_format($revenueSum, 2) . "</th>";
                            $revenueTotal += $revenueSum;
                        }
                        ?>
                        <th><?= number_format($revenueTotal, 2) ?></th>
                    </tr>
                    <tr>
                        <td>Customer discount</td>
                        <?php
                        $customerDiscountTotal = $customerDiscountTotalSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $customerDiscountTotalSum = ReportService::customerDiscountCalculationForReport($value);
                            echo "<td>" . number_format($customerDiscountTotalSum, 2) . "</td>";
                            $customerDiscountTotal += $customerDiscountTotalSum;
                        }
                        ?>
                        <td><?= number_format($customerDiscountTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Markup Amount</td>
                        <?php
                        $markupAmountTotal = $markupAmountTotalSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $markupAmountTotalSum = ReportService::markupCalculationForReport($value);
                            echo "<td>" . number_format($markupAmountTotalSum, 2) . "</td>";
                            $markupAmountTotal += $markupAmountTotalSum;
                        }
                        ?>
                        <td><?= number_format($markupAmountTotal, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Customer Service Charge</td>
                        <?php
                        $customerServiceChargeTotal = $customerServiceChargeTotalSum = 0;
                        foreach ($monthWiseTicketData as $key => $value) {
                            $customerServiceChargeTotalSum = ReportService::customerServiceChargeCalculationForReport($value);
                            echo "<td>" . number_format($customerServiceChargeTotalSum, 2) . "</td>";
                            $customerServiceChargeTotal += $customerServiceChargeTotalSum;
                        }
                        ?>
                        <td><?= number_format($customerServiceChargeTotal, 2) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("CUSTOMER_CATEGORY", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Customer Category Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $customerCategoryTotalQty = 0;
                    $customerCategoryTotalSegment = 0;
                    $customerCategoryTotalNetRevenueAfterDiscount = 0;
                    $customerCategoryTotalQuoteAmount = 0;
                    $customerCategoryTotalReceivedAmount = 0;
                    $customerCategoryTotalGross = 0;
                    foreach ($customerCategoryWiseData as $key => $categoryData) {
                        $gross = $quote = $netRevenue = 0;
                        $quote = ReportService::quoteAmountCalculationForReport($key, $categoryData, $customerCategoryWiseRefundData);
                        $gross = ReportService::grossCalculationForReport($categoryData);
                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $categoryData, $customerCategoryWiseRefundData);

                        $total = (double)array_sum(array_column($categoryData, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($categoryData, 'numberOfSegment'));
                        $receivedAmount = (double)array_sum(array_column($categoryData, 'receivedAmount'));
                        ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $customerCategoryTotalQty += $total;
                        $customerCategoryTotalSegment += $numberOfSegment;
                        $customerCategoryTotalQuoteAmount += $quote;
                        $customerCategoryTotalGross += $gross;
                        $customerCategoryTotalReceivedAmount += $receivedAmount;
                        $customerCategoryTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $customerCategoryTotalQty ?></th>
                        <th><?= $customerCategoryTotalSegment ?></th>
                        <th>BDT <?= number_format($customerCategoryTotalGross) ?></th>
                        <th>BDT <?= number_format($customerCategoryTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($customerCategoryTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($customerCategoryTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("BOOKING_TYPE", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label color">
                    Booking Type Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $bookingTypeWiseTotalQty = 0;
                    $bookingTypeWiseTotalSegment = 0;
                    $bookingTypeWiseTotalRevenue = 0;
                    $bookingTypeWiseTotalNetRevenueAfterDiscount = 0;
                    $bookingTypeWiseTotalQuoteAmount = 0;
                    $bookingTypeWiseTotalReceivedAmount = 0;
                    $bookingTypeWiseTotalGross = 0;
                    foreach ($bookingTypeWiseData as $key => $typeData) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($typeData);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $typeData, $bookingTypeWiseRefundData);
                        $total = (double)array_sum(array_column($typeData, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($typeData, 'numberOfSegment'));
                        $receivedAmount = (double)array_sum(array_column($typeData, 'receivedAmount'));

                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $typeData, $bookingTypeWiseRefundData);
                        ?>
                        <tr>
                            <td><?= Constant::BOOKING_TYPE[$key] ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $bookingTypeWiseTotalQty += $total;
                        $bookingTypeWiseTotalSegment += $numberOfSegment;
                        $bookingTypeWiseTotalQuoteAmount += $quote;
                        $bookingTypeWiseTotalGross += $gross;
                        $bookingTypeWiseTotalReceivedAmount += $receivedAmount;
                        $bookingTypeWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $bookingTypeWiseTotalQty ?></th>
                        <th><?= $bookingTypeWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($bookingTypeWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($bookingTypeWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($bookingTypeWiseTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($bookingTypeWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("FLIGHT_TYPE", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Flight Type Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $flightTypeWiseTotalQty = 0;
                    $flightTypeWiseTotalSegment = 0;
                    $flightTypeWiseTotalNetRevenueAfterDiscount = 0;
                    $flightTypeWiseTotalQuoteAmount = 0;
                    $flightTypeWiseTotalReceivedAmount = 0;
                    $flightTypeWiseTotalGross = 0;
                    foreach ($flightTypeWiseData as $key => $typeData) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($typeData);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $typeData, $flightTypeWiseRefundData);
                        $total = (double)array_sum(array_column($typeData, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($typeData, 'numberOfSegment'));
                        $receivedAmount = (double)array_sum(array_column($typeData, 'receivedAmount'));
                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $typeData, $flightTypeWiseRefundData);
                        ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $flightTypeWiseTotalQty += $total;
                        $flightTypeWiseTotalSegment += $numberOfSegment;
                        $flightTypeWiseTotalQuoteAmount += $quote;
                        $flightTypeWiseTotalGross += $gross;
                        $flightTypeWiseTotalReceivedAmount += $receivedAmount;
                        $flightTypeWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $flightTypeWiseTotalQty ?></th>
                        <th><?= $flightTypeWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($flightTypeWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($flightTypeWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($flightTypeWiseTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($flightTypeWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("GDS", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    GDS Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $gdsWiseTotalQty = 0;
                    $gdsWiseTotalSegment = 0;
                    $gdsWiseTotalNetRevenueAfterDiscount = 0;
                    $gdsWiseTotalQuoteAmount = 0;
                    $gdsWiseTotalReceivedAmount = 0;
                    $gdsWiseTotalGross = 0;

                    foreach ($gdsWiseData as $key => $gdsData) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($gdsData);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $gdsData, $gdsWiseRefundData);
                        $total = (double)array_sum(array_column($gdsData, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($gdsData, 'numberOfSegment'));
                        $receivedAmount = (double)array_sum(array_column($gdsData, 'receivedAmount'));

                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $gdsData, $gdsWiseRefundData);
                        ?>
                        <tr>
                            <td><?= $gdsData[0]['provider']['name'] ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $gdsWiseTotalQty += $total;
                        $gdsWiseTotalSegment += $numberOfSegment;
                        $gdsWiseTotalQuoteAmount += $quote;
                        $gdsWiseTotalGross += $gross;
                        $gdsWiseTotalReceivedAmount += $receivedAmount;
                        $gdsWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $gdsWiseTotalQty ?></th>
                        <th><?= $gdsWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($gdsWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($gdsWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($gdsWiseTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($gdsWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("AIRLINES", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Airline Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $airlineWiseTotalQty = 0;
                    $airlineWiseTotalSegment = 0;
                    $airlineWiseTotalQuoteAmount = 0;
                    $airlineWiseTotalReceivedAmount = 0;
                    $airlineWiseTotalGross = 0;
                    $airlineWiseTotalNetRevenueAfterDiscount = 0;

                    foreach ($airlineWiseData as $key => $airlineData) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($airlineData);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $airlineData, $airlineWiseRefundData);
                        $total = (double)array_sum(array_column($airlineData, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($airlineData, 'numberOfSegment'));
                        $receivedAmount = (double)array_sum(array_column($airlineData, 'receivedAmount'));

                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $airlineData, $airlineWiseRefundData);
                        ?>
                        <tr>
                            <td><?= $airlineData[0]['airlineName'] ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $airlineWiseTotalQty += $total;
                        $airlineWiseTotalSegment += $numberOfSegment;
                        $airlineWiseTotalQuoteAmount += $quote;
                        $airlineWiseTotalGross += $gross;
                        $airlineWiseTotalReceivedAmount += $receivedAmount;
                        $airlineWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $airlineWiseTotalQty ?></th>
                        <th><?= $airlineWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($airlineWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($airlineWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($airlineWiseTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($airlineWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("REFERENCE", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Reference Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $refWiseTotalQty = 0;
                    $refWiseTotalSegment = 0;
                    $refWiseTotalNetRevenueAfterDiscount = 0;
                    $refWiseTotalQuoteAmount = 0;
                    $refWiseTotalReceivedAmount = 0;
                    $refWiseTotalGross = 0;
                    foreach ($refWiseData as $key => $refData) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($refData);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $refData, $refWiseRefundData);
                        $total = (double)array_sum(array_column($refData, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($refData, 'numberOfSegment'));
                        $receivedAmount = (double)array_sum(array_column($refData, 'receivedAmount'));

                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $refData, $refWiseRefundData);
                        ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $refWiseTotalQty += $total;
                        $refWiseTotalSegment += $numberOfSegment;
                        $refWiseTotalQuoteAmount += $quote;
                        $refWiseTotalGross += $gross;
                        $refWiseTotalReceivedAmount += $receivedAmount;
                        $refWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $refWiseTotalQty ?></th>
                        <th><?= $refWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($refWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($refWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($refWiseTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($refWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("SUPPLIER", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Supplier Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Paid Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $supplierWiseTotalQty = 0;
                    $supplierWiseTotalSegment = 0;
                    $supplierWiseTotalQuoteAmount = 0;
                    $supplierWiseTotalPaidAmount = 0;
                    $supplierWiseTotalGross = 0;
                    $supplierWiseTotalNetRevenueAfterDiscount = 0;
                    foreach ($supplierWiseData as $key => $supplierData) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($supplierData);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $supplierData, $supplierWiseRefundData);
                        $total = (double)array_sum(array_column($supplierData, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($supplierData, 'numberOfSegment'));
                        $paidAmount = (double)array_sum(array_column($supplierData, 'paidAmount'));

                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $supplierData, $supplierWiseRefundData);
                        ?>
                        <tr>
                            <td><?= $supplierData[0]['supplierCompany'] ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($paidAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $supplierWiseTotalSegment += $numberOfSegment;
                        $supplierWiseTotalQuoteAmount += $quote;
                        $supplierWiseTotalGross += $gross;
                        $supplierWiseTotalPaidAmount += $paidAmount;
                        $supplierWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $supplierWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($supplierWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($supplierWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($supplierWiseTotalPaidAmount) ?></th>
                        <th>BDT <?= number_format($supplierWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("EMPLOYEE", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Employee Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $employeeWiseTotalQty = 0;
                    $employeeWiseTotalSegment = 0;
                    $employeeWiseTotalQuoteAmount = 0;
                    $employeeWiseTotalReceivedAmount = 0;
                    $employeeWiseTotalDueAmount = 0;
                    $employeeWiseTotalGross = 0;
                    $employeeWiseTotalNetRevenueAfterDiscount = 0;
                    foreach ($employeeWiseData as $key => $employeeData) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($employeeData);

                        $quote = ReportService::quoteAmountCalculationForReport($key, $employeeData, $employeeWiseRefundData);
                        $total = (double)array_sum(array_column($employeeData, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($employeeData, 'numberOfSegment'));
                        $receivedAmount = (double)array_sum(array_column($employeeData, 'receivedAmount'));

                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $employeeData, $employeeWiseRefundData);
                        ?>
                        <tr>
                            <td><?= Employee::findOne(['userId' => $employeeData[0]['user']['id']])->fullName ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $employeeWiseTotalQty += $total;
                        $employeeWiseTotalSegment += $numberOfSegment;
                        $employeeWiseTotalQuoteAmount += $quote;
                        $employeeWiseTotalGross += $gross;
                        $employeeWiseTotalReceivedAmount += $receivedAmount;
                        $employeeWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $employeeWiseTotalQty ?></th>
                        <th><?= $employeeWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($employeeWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($employeeWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($employeeWiseTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($employeeWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("ROUTING", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Routing Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $routingWiseTotalQty = 0;
                    $routingWiseTotalSegment = 0;
                    $routingWiseTotalQuoteAmount = 0;
                    $routingWiseTotalReceivedAmount = 0;
                    $routingWiseTotalGross = 0;

                    $routingWiseTotalRevenueBeforeDiscount = 0;
                    $routingWiseTotalNetRevenueAfterDiscount = 0;
                    foreach ($routingWiseData as $key => $routingdata) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($routingdata);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $routingdata, $routingWiseRefundData);
                        $total = (double)array_sum(array_column($routingdata, 'total'));
                        $numberOfSegment = (double)array_sum(array_column($routingdata, 'numberOfSegment'));
                        $receivedAmount = (double)array_sum(array_column($routingdata, 'receivedAmount'));

                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $routingdata, $routingWiseRefundData);
                        ?>
                        <tr>
                            <td><?= $key ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $routingWiseTotalQty += $total;
                        $routingWiseTotalSegment += $numberOfSegment;
                        $routingWiseTotalQuoteAmount += $quote;
                        $routingWiseTotalGross += $gross;
                        $routingWiseTotalReceivedAmount += $receivedAmount;
                        $routingWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th>Total</th>
                        <th><?= $routingWiseTotalQty ?></th>
                        <th><?= $routingWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($routingWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($routingWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($routingWiseTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($routingWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("CUSTOMER", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Customer Wise Ticket Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Total Segment</th>
                        <th>Gross Before Discount</th>
                        <th>Total Quote</th>
                        <th style="background-color: #e6e8e6">Received Amount</th>
                        <th style="background-color: lightgrey">Net Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $customerWiseTotalQty = 0;
                    $customerWiseTotalSegment = 0;
                    $customerWiseTotalQuoteAmount = 0;
                    $customerWiseTotalReceivedAmount = 0;
                    $customerWiseTotalGross = 0;
                    $customerWiseTotalRevenueBeforeDiscount = 0;
                    $customerWiseTotalNetRevenueAfterDiscount = 0;

                    foreach ($customerWiseData as $key => $customerdata) {
                        $gross = $quote = $netRevenue = 0;
                        $gross = ReportService::grossCalculationForReport($customerdata);
                        $quote = ReportService::quoteAmountCalculationForReport($key, $customerdata, $customerWiseRefundData);
                        $total = (double)$customerdata['total'];
                        $numberOfSegment = (double)$customerdata['numberOfSegment'];
                        $receivedAmount = (double)$customerdata['receivedAmount'];

                        $netRevenueAfterDiscount = ReportService::netRevenueForReport($key, $customerdata, $customerWiseRefundData);
                        ?>
                        <tr>
                            <td><?= $customerdata['customer']['company'] ?></td>
                            <td><?= $customerdata['customer']['category'] ?></td>
                            <td><?= $total ?></td>
                            <td><?= $numberOfSegment ?></td>
                            <td>BDT <?= number_format($gross) ?></td>
                            <td>BDT <?= number_format($quote) ?></td>
                            <td style="background-color: #e6e8e6"> BDT <?= number_format($receivedAmount) ?></td>
                            <td style="background-color: lightgrey">
                                BDT <?= number_format($netRevenueAfterDiscount) ?></td>
                        </tr>
                        <?php
                        $customerWiseTotalQty += $total;
                        $customerWiseTotalSegment += $numberOfSegment;
                        $customerWiseTotalQuoteAmount += $quote;
                        $customerWiseTotalGross += $gross;
                        $customerWiseTotalReceivedAmount += $receivedAmount;
                        $customerWiseTotalNetRevenueAfterDiscount += $netRevenueAfterDiscount;
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th colspan="2">Total</th>
                        <th><?= $customerWiseTotalQty ?></th>
                        <th><?= $customerWiseTotalSegment ?></th>
                        <th>BDT <?= number_format($customerWiseTotalGross) ?></th>
                        <th>BDT <?= number_format($customerWiseTotalQuoteAmount) ?></th>
                        <th>BDT <?= number_format($customerWiseTotalReceivedAmount) ?></th>
                        <th>BDT <?= number_format($customerWiseTotalNetRevenueAfterDiscount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("AIRLINE_ROUTING_REPORT", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Airlines Routing Wise Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Airline Name</th>
                        <th>Routing</th>
                        <th>Qty</th>
                        <th>Segment</th>
                        <th>Average Fare</th>
                        <th>Total Sale</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $airlinesTotalQty = 0;
                    $airlinesTotalSegment = 0;
                    $airlinesTotalAmount = 0;
                    $airlinesTotalAvg = 0;
                    foreach ($airlineRoutingWiseReport as $key => $single) {
                        $airlinesTotalQty += $single['total'];
                        $airlinesTotalSegment += $single['numberOfSegment'];
                        $airlinesTotalAvg += ($single['baseFare'] / $single['total']);
                        $airlinesTotalAmount += $single['quoteAmount'];
                        ?>
                        <tr>
                            <td><?= $single['airlineName'] ?></td>
                            <td><?= $single['code'] ?></td>
                            <td><?= $single['routing'] ?></td>
                            <td><?= $single['total'] ?></td>
                            <td><?= $single['numberOfSegment'] ?></td>
                            <td><?= number_format(($single['baseFare'] / $single['total'])) ?></td>
                            <td>BDT <?= number_format($single['quoteAmount']) ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th colspan="3">Total</th>
                        <th><?= number_format($airlinesTotalQty) ?></th>
                        <th><?= number_format($airlinesTotalSegment) ?></th>
                        <th>BDT <?= number_format($airlinesTotalAvg) ?></th>
                        <th>BDT <?= number_format($airlinesTotalAmount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("AIRLINE_INTERNATIONAL_ROUTING_REPORT", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Airline's International Routing Wise Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Airline Name</th>
                        <th>Routing</th>
                        <th>Airport</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Region</th>
                        <th>Qty</th>
                        <th>Segment</th>
                        <th>Average Fare</th>
                        <th>Total Sale</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $airlinesTotalQty = 0;
                    $airlinesTotalSegment = 0;
                    $airlinesTotalAmount = 0;
                    $airlinesTotalAvg = 0;
                    foreach ($airlinesInternationalRoutingWiseReport as $key => $single) {
                        $airlinesTotalQty += $single['total'];
                        $airlinesTotalSegment += $single['numberOfSegment'];
                        $airlinesTotalAvg += ($single['baseFare'] / $single['total']);
                        $airlinesTotalAmount += $single['sum'];

                        $airports = array_unique(explode('-', str_replace(' ', '', strtoupper($single['routing']))));
                        $international = array_values(array_diff($airports, Constant::BD_AIRPORTS));
                        $airportDetails = empty($international) ? null : FlightAirport::findOne(['iata' => $international[0]]);
                        ?>
                        <tr>
                            <td><?= $single['airlineName'] ?></td>
                            <td><?= $single['code'] ?></td>
                            <td><?= $single['routing'] ?></td>
                            <td><?= $airportDetails ? $airportDetails->iata : '' ?></td>
                            <td><?= $airportDetails ? $airportDetails->city : '' ?></td>
                            <td><?= $airportDetails ? $airportDetails->country : '' ?></td>
                            <td><?= $airportDetails ? $airportDetails->region : '' ?></td>
                            <td><?= $single['total'] ?></td>
                            <td><?= $single['numberOfSegment'] ?></td>
                            <td><?= number_format(($single['baseFare'] / $single['total'])) ?></td>
                            <td>BDT <?= number_format($single['sum']) ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th colspan="7">Total</th>
                        <th><?= number_format($airlinesTotalQty) ?></th>
                        <th><?= number_format($airlinesTotalSegment) ?></th>
                        <th>BDT <?= number_format($airlinesTotalAvg) ?></th>
                        <th>BDT <?= number_format($airlinesTotalAmount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("AIRLINE_DOMESTIC_ROUTING_REPORT", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Airline's Domestic Routing Wise Report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Airline Name</th>
                        <th>Routing</th>
                        <th>Qty</th>
                        <th>Segment</th>
                        <th>Average Fare</th>
                        <th>Total Sale</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $airlinesTotalQty = 0;
                    $airlinesTotalSegment = 0;
                    $airlinesTotalAmount = 0;
                    $airlinesTotalAvg = 0;
                    foreach ($airlinesDomesticRoutingWiseReport as $key => $single) {
                        $airlinesTotalQty += $single['total'];
                        $airlinesTotalSegment += $single['numberOfSegment'];
                        $airlinesTotalAvg += ($single['baseFare'] / $single['total']);
                        $airlinesTotalAmount += $single['sum'];
                        ?>
                        <tr>
                            <td><?= $single['airlineName'] ?></td>
                            <td><?= $single['code'] ?></td>
                            <td><?= $single['routing'] ?></td>
                            <td><?= $single['total'] ?></td>
                            <td><?= $single['numberOfSegment'] ?></td>
                            <td><?= number_format(($single['baseFare'] / $single['total'])) ?></td>
                            <td>BDT <?= number_format($single['sum']) ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #ccc;">
                        <th colspan="3">Total</th>
                        <th><?= number_format($airlinesTotalQty) ?></th>
                        <th><?= number_format($airlinesTotalSegment) ?></th>
                        <th>BDT <?= number_format($airlinesTotalAvg) ?></th>
                        <th>BDT <?= number_format($airlinesTotalAmount) ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (in_array("FLIGHT_TYPE_AIRLINE_REPORT", $getReportType)) { ?>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Airline wise flight type report
                </h3>
            </div>
            <div class="card-toolbar">
                <small class="pull-right">Date: <?= $date ?></small>
            </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Segments</th>
                        <th>Qty</th>
                        <th>Total Sale</th>
                        <th>Average Sale</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $supplierTotalQty = 0;
                    $supplierTotalQuoteAmount = 0;

                    foreach ($flightTypeData as $flightType => $value) {
                        $allTotal = array_sum(array_column($value, 'total'));
                        $numberOfSegment = array_sum(array_column($value, 'numberOfSegment'));
                        $quote = ReportService::quoteAmountCalculationForReport($flightType, $value, $flightTypeRefundData);
                        $total = isset($flightTypeRefundData[$flightType]) ? ($allTotal + $flightTypeRefundData[$flightType]['total']) : $allTotal;
                        if ($total == 0){
                            dd([$flightTypeData, $value]);
                        }
                        ?>
                        <tr style="background-color: lightgrey">
                            <th><?= $flightType ?></th>
                            <th><?= $numberOfSegment ?></th>
                            <th><?= $total ?></th>
                            <th>BDT <?= number_format($quote) ?></th>
                            <th>BDT <?= number_format(($quote / $total), 2) ?></th>
                        </tr>
                        <?php
                        foreach ($flightTypeWiseAirlineData[$flightType] as $airline => $item) {

                            $total = array_sum(array_column($item, 'total'));
                            $numberOfSegment = array_sum(array_column($item, 'numberOfSegment'));
                            $quote = ReportService::quoteAmountCalculationForReport($airline, $item, $flightTypeWiseAirlineRefundData[$flightType]);
                            ?>
                            <tr>
                                <td><?= $airline ?></td>
                                <td><?= $numberOfSegment ?></td>
                                <td><?= $total ?></td>
                                <td>BDT <?= number_format($quote) ?></td>
                                <td>BDT <?= number_format(($quote / $total), 2) ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

