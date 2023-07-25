<?php

use app\components\Constant;
use app\modules\sales\models\FlightAirport;
use app\modules\sales\components\TicketComponent;
use app\modules\sales\services\ReportService;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Hotel Reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hotels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$getReportType = [];
if (isset(Yii::$app->request->get()["reportType"])) {
    $getReportType = Yii::$app->request->get()["reportType"];
}
?>

<div class="package-report">
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
                <div class="col-md" style="padding-top:24px; padding-right:0">
                    <?= Html::submitButton(Yii::t('app', 'Generate Report'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    <p class="lead">Category Wise Report</p>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Total Nights</th>
                            <th>Cost Of Sale</th>
                            <th>Quote Amount</th>
                            <th>Received Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = $totalNights = $totalQuote = $totalCost = $totalReceived = 0;
                        foreach ($customerCategoryWiseData as $key => $dataArray) {
                            $qty = ReportService::packageQuantityCalculationForReport($dataArray);
                            $nights = ReportService::hotelTotalNightsCalculationForReport($dataArray);
                            $costOfSale = ReportService::packageCostOfSaleCalculationForReport($dataArray);
                            $quoteAmount = ReportService::packageQuoteAmountCalculationForReport($dataArray);
                            $receivedAmount = ReportService::packageReceivedAmountCalculationForReport($dataArray);
                            ?>
                            <tr>
                                <td><?= $key ?></td>
                                <td><?= $qty ?></td>
                                <td><?= $nights ?></td>
                                <td><?= number_format($costOfSale) ?></td>
                                <td><?= number_format($quoteAmount) ?></td>
                                <td><?= number_format($receivedAmount) ?></td>
                            </tr>
                            <?php
                            $total += $qty;
                            $totalNights += $nights;
                            $totalQuote += $quoteAmount;
                            $totalCost += $costOfSale;
                            $totalReceived += $receivedAmount;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #ccc;">
                            <th>Total</th>
                            <th><?= number_format($total) ?></th>
                            <th><?= number_format($totalNights) ?></th>
                            <th><?= number_format($totalCost) ?></th>
                            <th><?= number_format($totalQuote) ?></th>
                            <th><?= number_format($totalReceived) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    <p class="lead">Booking Type Wise Report</p>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Total Nights</th>
                            <th>Cost Of Sale</th>
                            <th>Quote Amount</th>
                            <th>Received Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = $totalNights = $totalQuote = $totalCost = $totalReceived = 0;
                        foreach ($bookingTypeWiseData as $key => $dataArray) {
                            $qty = ReportService::packageQuantityCalculationForReport($dataArray);
                            $nights = ReportService::hotelTotalNightsCalculationForReport($dataArray);
                            $costOfSale = ReportService::packageCostOfSaleCalculationForReport($dataArray);
                            $quoteAmount = ReportService::packageQuoteAmountCalculationForReport($dataArray);
                            $receivedAmount = ReportService::packageReceivedAmountCalculationForReport($dataArray);
                            ?>
                            <tr>
                                <td><?= $key ?></td>
                                <td><?= $qty ?></td>
                                <td><?= $nights ?></td>
                                <td><?= number_format($costOfSale) ?></td>
                                <td><?= number_format($quoteAmount) ?></td>
                                <td><?= number_format($receivedAmount) ?></td>
                            </tr>
                            <?php
                            $total += $qty;
                            $totalNights += $nights;
                            $totalQuote += $quoteAmount;
                            $totalCost += $costOfSale;
                            $totalReceived += $receivedAmount;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #ccc;">
                            <th>Total</th>
                            <th><?= number_format($total) ?></th>
                            <th><?= number_format($totalNights) ?></th>
                            <th><?= number_format($totalCost) ?></th>
                            <th><?= number_format($totalQuote) ?></th>
                            <th><?= number_format($totalReceived) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    <p class="lead">Route Wise Report</p>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Total Nights</th>
                            <th>Cost Of Sale</th>
                            <th>Quote Amount</th>
                            <th>Received Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = $totalNights = $totalQuote = $totalCost = $totalReceived = 0;
                        foreach ($routeWiseData as $key => $dataArray) {
                            $qty = ReportService::packageQuantityCalculationForReport($dataArray);
                            $nights = ReportService::hotelTotalNightsCalculationForReport($dataArray);
                            $costOfSale = ReportService::packageCostOfSaleCalculationForReport($dataArray);
                            $quoteAmount = ReportService::packageQuoteAmountCalculationForReport($dataArray);
                            $receivedAmount = ReportService::packageReceivedAmountCalculationForReport($dataArray);
                            ?>
                            <tr>
                                <td><?= $key ?></td>
                                <td><?= $qty ?></td>
                                <td><?= $nights ?></td>
                                <td><?= number_format($costOfSale) ?></td>
                                <td><?= number_format($quoteAmount) ?></td>
                                <td><?= number_format($receivedAmount) ?></td>
                            </tr>
                            <?php
                            $total += $qty;
                            $totalNights += $nights;
                            $totalQuote += $quoteAmount;
                            $totalCost += $costOfSale;
                            $totalReceived += $receivedAmount;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #ccc;">
                            <th>Total</th>
                            <th><?= number_format($total) ?></th>
                            <th><?= number_format($totalNights) ?></th>
                            <th><?= number_format($totalCost) ?></th>
                            <th><?= number_format($totalQuote) ?></th>
                            <th><?= number_format($totalReceived) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    <p class="lead">Customer Wise Report</p>
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Total Nights</th>
                            <th>Cost Of Sale</th>
                            <th>Quote Amount</th>
                            <th>Received Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total = $totalNights = $totalQuote = $totalCost = $totalReceived = 0;
                        foreach ($customerWiseData as $key => $dataArray) {
                            $qty = ReportService::packageQuantityCalculationForReport($dataArray);
                            $nights = ReportService::hotelTotalNightsCalculationForReport($dataArray);
                            $costOfSale = ReportService::packageCostOfSaleCalculationForReport($dataArray);
                            $quoteAmount = ReportService::packageQuoteAmountCalculationForReport($dataArray);
                            $receivedAmount = ReportService::packageReceivedAmountCalculationForReport($dataArray);
                            ?>
                            <tr>
                                <td><?= $key ?></td>
                                <td><?= $qty ?></td>
                                <td><?= $nights ?></td>
                                <td><?= number_format($costOfSale) ?></td>
                                <td><?= number_format($quoteAmount) ?></td>
                                <td><?= number_format($receivedAmount) ?></td>
                            </tr>
                            <?php
                            $total += $qty;
                            $totalNights += $nights;
                            $totalQuote += $quoteAmount;
                            $totalCost += $costOfSale;
                            $totalReceived += $receivedAmount;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #ccc;">
                            <th>Total</th>
                            <th><?= number_format($total) ?></th>
                            <th><?= number_format($totalNights) ?></th>
                            <th><?= number_format($totalCost) ?></th>
                            <th><?= number_format($totalQuote) ?></th>
                            <th><?= number_format($totalReceived) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



