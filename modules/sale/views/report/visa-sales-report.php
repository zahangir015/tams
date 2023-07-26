<?php

use app\components\Constant;
use app\components\GlobalConstant;
use app\modules\sales\models\FlightAirport;
use app\modules\sales\components\TicketComponent;
use app\modules\sales\services\ReportService;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Visa Reports');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Visa List'), 'url' => ['/sale/visa/index']];
$this->params['breadcrumbs'][] = $this->title;
$getReportType = [];
if (isset(Yii::$app->request->get()["reportType"])) {
    $getReportType = Yii::$app->request->get()["reportType"];
}
?>

<div class="visa-report">
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Generate Report
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
                    <?php
                    echo '<label class="control-label">Report Type</label>';
                    echo Select2::widget([
                        'name' => 'reportType',
                        'value' => $getReportType,
                        'data' => GlobalConstant::VISA_REPORT_TYPE,
                        'theme' => Select2::THEME_DEFAULT,
                        'options' => ['multiple' => true, 'placeholder' => 'Select Report Type ...', 'class' => 'form-control']
                    ]);
                    ?>
                </div>
                <div class="col-md" style="padding-top:30px; padding-right:0">
                    <?= Html::submitButton(Yii::t('app', 'Generate Report'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if (in_array("COUNTRY", $getReportType)) { ?>
        <div class="card mb-5">
            <div class="card-header">
                <div class="card-title">
                    Country Wise Ticket Report(<?= $date ?>)
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
                            <th>Gross</th>
                            <th>Total Quote</th>
                            <th>Total Received</th>
                            <th>Total Due</th>
                            <th>Net Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $countryTotalQty = 0;
                        $countryTotalSegment = 0;
                        $countryTotalNetProfit = 0;
                        $countryTotalQuoteAmount = 0;
                        $countryTotalReceivedAmount = 0;
                        $countryTotalDueAmount = 0;
                        $countryTotalGross = 0;
                        foreach ($countryWiseData as $categoryData) {
                            $gross = ($categoryData['baseFare'] + $categoryData['tax'] + $categoryData['otherTax']);
                            $due = ($categoryData['quoteAmount'] - $categoryData['receivedAmount']);
                            ?>
                            <tr>
                                <td><?= $categoryData['country']['name'] ?></td>
                                <td><?= $categoryData['total'] ?></td>
                                <td><?= $categoryData['numberOfSegment'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($categoryData['quoteAmount']) ?></td>
                                <td><?= number_format($categoryData['receivedAmount']) ?></td>
                                <td><?= number_format($due) ?></td>
                                <td><?= number_format($categoryData['netProfit']) ?></td>
                            </tr>
                            <?php
                            $countryTotalQty += $categoryData['total'];
                            $countryTotalSegment += $categoryData['numberOfSegment'];
                            $countryTotalQuoteAmount += $categoryData['quoteAmount'];
                            $countryTotalReceivedAmount += $categoryData['receivedAmount'];
                            $countryTotalDueAmount += $due;
                            $countryTotalGross += $gross;
                            $countryTotalNetProfit += $categoryData['netProfit'];
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #8eae7f;">
                            <th>Total</th>
                            <th><?= $countryTotalQty ?></th>
                            <th><?= $countryTotalSegment ?></th>
                            <th><?= number_format($countryTotalGross) ?></th>
                            <th><?= number_format($countryTotalQuoteAmount) ?></th>
                            <th><?= number_format($countryTotalReceivedAmount) ?></th>
                            <th><?= number_format($countryTotalDueAmount) ?></th>
                            <th><?= number_format($countryTotalNetProfit) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (in_array("CUSTOMER_CATEGORY", $getReportType)) { ?>
        <div class="card mb-5">
            <div class="card-header">
                <div class="card-title">
                    Customer Category Wise Ticket Report(<?= $date ?>)
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
                            <th>Gross</th>
                            <th>Total Quote</th>
                            <th>Total Received</th>
                            <th>Total Due</th>
                            <th>Net Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $customerCategoryTotalQty = 0;
                        $customerCategoryTotalSegment = 0;
                        $customerCategoryTotalNetProfit = 0;
                        $customerCategoryTotalQuoteAmount = 0;
                        $customerCategoryTotalReceivedAmount = 0;
                        $customerCategoryTotalDueAmount = 0;
                        $customerCategoryTotalGross = 0;
                        foreach ($customerCategoryWiseData as $categoryData) {
                            $gross = ($categoryData['baseFare'] + $categoryData['tax'] + $categoryData['otherTax']);
                            $due = ($categoryData['quoteAmount'] - $categoryData['receivedAmount']);
                            ?>
                            <tr>
                                <td><?= $categoryData['customerCategory'] ?></td>
                                <td><?= $categoryData['total'] ?></td>
                                <td><?= $categoryData['numberOfSegment'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($categoryData['quoteAmount']) ?></td>
                                <td><?= number_format($categoryData['receivedAmount']) ?></td>
                                <td><?= number_format($due) ?></td>
                                <td><?= number_format($categoryData['netProfit']) ?></td>
                            </tr>
                            <?php
                            $customerCategoryTotalQty += $categoryData['total'];
                            $customerCategoryTotalSegment += $categoryData['numberOfSegment'];
                            $customerCategoryTotalQuoteAmount += $categoryData['quoteAmount'];
                            $customerCategoryTotalReceivedAmount += $categoryData['receivedAmount'];
                            $customerCategoryTotalDueAmount += $due;
                            $customerCategoryTotalGross += $gross;
                            $customerCategoryTotalNetProfit += $categoryData['netProfit'];
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #8eae7f;">
                            <th>Total</th>
                            <th><?= $customerCategoryTotalQty ?></th>
                            <th><?= $customerCategoryTotalSegment ?></th>
                            <th><?= number_format($customerCategoryTotalGross) ?></th>
                            <th><?= number_format($customerCategoryTotalQuoteAmount) ?></th>
                            <th><?= number_format($customerCategoryTotalReceivedAmount) ?></th>
                            <th><?= number_format($customerCategoryTotalDueAmount) ?></th>
                            <th><?= number_format($customerCategoryTotalNetProfit) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (in_array("BOOKING_TYPE", $getReportType)) { ?>
        <div class="card mb-5">
            <div class="card-header">
                <div class="card-title">
                    Booking Type Wise Ticket Report(<?= $date ?>)
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
                            <th>Gross</th>
                            <th>Total Quote</th>
                            <th>Total Received</th>
                            <th>Total Due</th>
                            <th>Net Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $bookingTypeTotalQty = 0;
                        $bookingTypeTotalSegment = 0;
                        $bookingTypeTotalNetProfit = 0;
                        $bookingTypeTotalQuoteAmount = 0;
                        $bookingTypeTotalReceivedAmount = 0;
                        $bookingTypeTotalDueAmount = 0;
                        $bookingTypeTotalGross = 0;
                        foreach ($bookingTypeWiseData as $typeData) {
                            $gross = ($typeData['baseFare'] + $typeData['tax'] + $typeData['otherTax']);
                            $due = ($typeData['quoteAmount'] - $typeData['receivedAmount']);
                            ?>
                            <tr>
                                <td><?= ServiceConstant::BOOKING_TYPE[$typeData['bookedOnline']] ?></td>
                                <td><?= $typeData['total'] ?></td>
                                <td><?= $typeData['numberOfSegment'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($typeData['quoteAmount']) ?></td>
                                <td><?= number_format($typeData['receivedAmount']) ?></td>
                                <td><?= number_format($due) ?></td>
                                <td><?= number_format($typeData['netProfit']) ?></td>
                            </tr>
                            <?php
                            $bookingTypeTotalQty += $typeData['total'];
                            $bookingTypeTotalSegment += $typeData['numberOfSegment'];
                            $bookingTypeTotalQuoteAmount += $typeData['quoteAmount'];
                            $bookingTypeTotalReceivedAmount += $typeData['receivedAmount'];
                            $bookingTypeTotalNetProfit += $typeData['netProfit'];
                            $bookingTypeTotalDueAmount += $due;
                            $bookingTypeTotalGross += $gross;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #8eae7f;">
                            <th>Total</th>
                            <th><?= $bookingTypeTotalQty ?></th>
                            <th><?= $bookingTypeTotalSegment ?></th>
                            <th><?= number_format($bookingTypeTotalGross) ?></th>
                            <th><?= number_format($bookingTypeTotalQuoteAmount) ?></th>
                            <th><?= number_format($bookingTypeTotalReceivedAmount) ?></th>
                            <th><?= number_format($bookingTypeTotalDueAmount) ?></th>
                            <th><?= number_format($bookingTypeTotalNetProfit) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (in_array("CUSTOMER_CATEGORY_BOOKING_TYPE", $getReportType)) { ?>
        <div class="card mb-5">
            <div class="card-header">
                <div class="card-title">
                    Customer Category & Booking Type Wise Ticket Report(<?= $date ?>)
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
                            <th>Gross</th>
                            <th>Total Quote</th>
                            <th>Total Received</th>
                            <th>Total Due</th>
                            <th>Net Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $customerCategoryBookingTypeTotalQty = 0;
                        $customerCategoryBookingTypeTotalSegment = 0;
                        $customerCategoryBookingTypeTotalNetProfit = 0;
                        $customerCategoryBookingTypeTotalQuoteAmount = 0;
                        $customerCategoryBookingTypeTotalReceivedAmount = 0;
                        $customerCategoryBookingTypeTotalDueAmount = 0;
                        $customerCategoryBookingTypeTotalGross = 0;
                        foreach ($customerCategoryBookingTypeWiseData as $typeData) {
                            $gross = ($typeData['baseFare'] + $typeData['tax'] + $typeData['otherTax']);
                            $due = ($typeData['quoteAmount'] - $typeData['receivedAmount']);
                            ?>
                            <tr>
                                <td><?= $typeData['customerCategory'] . ' ' . ServiceConstant::BOOKING_TYPE[$typeData['bookedOnline']] ?></td>
                                <td><?= $typeData['total'] ?></td>
                                <td><?= $typeData['numberOfSegment'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($typeData['quoteAmount']) ?></td>
                                <td><?= number_format($typeData['receivedAmount']) ?></td>
                                <td><?= number_format($due) ?></td>
                                <td><?= number_format($typeData['netProfit']) ?></td>
                            </tr>
                            <?php
                            $customerCategoryBookingTypeTotalQty += $typeData['total'];
                            $customerCategoryBookingTypeTotalSegment += $typeData['numberOfSegment'];
                            $customerCategoryBookingTypeTotalQuoteAmount += $typeData['quoteAmount'];
                            $customerCategoryBookingTypeTotalReceivedAmount += $typeData['receivedAmount'];
                            $customerCategoryBookingTypeTotalNetProfit += $typeData['netProfit'];
                            $customerCategoryBookingTypeTotalDueAmount += $due;
                            $customerCategoryBookingTypeTotalGross += $gross;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #8eae7f;">
                            <th>Total</th>
                            <th><?= $customerCategoryBookingTypeTotalQty ?></th>
                            <th><?= $customerCategoryBookingTypeTotalSegment ?></th>
                            <th><?= number_format($customerCategoryBookingTypeTotalGross) ?></th>
                            <th><?= number_format($customerCategoryBookingTypeTotalQuoteAmount) ?></th>
                            <th><?= number_format($customerCategoryBookingTypeTotalReceivedAmount) ?></th>
                            <th><?= number_format($customerCategoryBookingTypeTotalDueAmount) ?></th>
                            <th><?= number_format($customerCategoryBookingTypeTotalNetProfit) ?></th>
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
                    Supplier Wise Ticket Report(<?= $date ?>)
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
                            <th>Gross</th>
                            <th>Total Quote</th>
                            <th>Total Received</th>
                            <th>Total Due</th>
                            <th>Net Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $supplierTotalQty = 0;
                        $supplierTotalSegment = 0;
                        $supplierTotalNetProfit = 0;
                        $supplierTotalQuoteAmount = 0;
                        $supplierTotalReceivedAmount = 0;
                        $supplierTotalDueAmount = 0;
                        $supplierTotalGross = 0;
                        foreach ($supplierWiseData as $supplierData) {
                            $gross = ($supplierData['baseFare'] + $supplierData['tax'] + $supplierData['otherTax']);
                            $due = ($supplierData['costOfSale'] - $supplierData['paidAmount']);
                            ?>
                            <tr>
                                <td><?= $supplierData['name'] . '(' . $supplierData['company'] . ')' ?></td>
                                <td><?= $supplierData['total'] ?></td>
                                <td><?= $supplierData['numberOfSegment'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($supplierData['quoteAmount']) ?></td>
                                <td><?= number_format($supplierData['receivedAmount']) ?></td>
                                <td><?= number_format($due) ?></td>
                                <td><?= number_format($supplierData['netProfit']) ?></td>
                            </tr>
                            <?php
                            $supplierTotalQty += $supplierData['total'];
                            $supplierTotalSegment += $supplierData['numberOfSegment'];
                            $supplierTotalQuoteAmount += $supplierData['quoteAmount'];
                            $supplierTotalReceivedAmount += $supplierData['receivedAmount'];
                            $supplierTotalNetProfit += $supplierData['netProfit'];
                            $supplierTotalDueAmount += $due;
                            $supplierTotalGross += $gross;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #8eae7f;">
                            <th>Total</th>
                            <th><?= $supplierTotalQty ?></th>
                            <th><?= $supplierTotalSegment ?></th>
                            <th><?= number_format($supplierTotalGross) ?></th>
                            <th><?= number_format($supplierTotalQuoteAmount) ?></th>
                            <th><?= number_format($supplierTotalReceivedAmount) ?></th>
                            <th><?= number_format($supplierTotalDueAmount) ?></th>
                            <th><?= number_format($supplierTotalNetProfit) ?></th>
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
                    Routing Wise Ticket Report(<?= $date ?>)
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
                            <th>Gross</th>
                            <th>Total Quote</th>
                            <th>Total Received</th>
                            <th>Total Due</th>
                            <th>Net Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $routeTotalQty = 0;
                        $routeTotalSegment = 0;
                        $routeTotalNetProfit = 0;
                        $routeTotalQuoteAmount = 0;
                        $routeTotalReceivedAmount = 0;
                        $routeTotalDueAmount = 0;
                        $routeTotalGross = 0;
                        foreach ($routingWiseData as $routeData) {
                            $gross = ($routeData['baseFare'] + $routeData['tax'] + $routeData['otherTax']);
                            $due = ($routeData['quoteAmount'] - $routeData['receivedAmount']);
                            ?>
                            <tr>
                                <td><?= $routeData['route'] ?></td>
                                <td><?= $routeData['total'] ?></td>
                                <td><?= $routeData['numberOfSegment'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($routeData['quoteAmount']) ?></td>
                                <td><?= number_format($routeData['receivedAmount']) ?></td>
                                <td><?= number_format($due) ?></td>
                                <td><?= number_format($routeData['netProfit']) ?></td>
                            </tr>
                            <?php
                            $routeTotalQty += $routeData['total'];
                            $routeTotalSegment += $routeData['numberOfSegment'];
                            $routeTotalQuoteAmount += $routeData['quoteAmount'];
                            $routeTotalReceivedAmount += $routeData['receivedAmount'];
                            $routeTotalNetProfit += $routeData['netProfit'];
                            $routeTotalDueAmount += $due;
                            $routeTotalGross += $gross;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #8eae7f;">
                            <th>Total</th>
                            <th><?= $routeTotalQty ?></th>
                            <th><?= $routeTotalSegment ?></th>
                            <th><?= number_format($routeTotalGross) ?></th>
                            <th><?= number_format($routeTotalQuoteAmount) ?></th>
                            <th><?= number_format($routeTotalReceivedAmount) ?></th>
                            <th><?= number_format($routeTotalDueAmount) ?></th>
                            <th><?= number_format($routeTotalNetProfit) ?></th>
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
                    Customer Wise Ticket Report(<?= $date ?>)
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
                            <th>Gross</th>
                            <th>Total Quote</th>
                            <th>Total Received</th>
                            <th>Total Due</th>
                            <th>Net Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $customerTotalQty = 0;
                        $customerTotalSegment = 0;
                        $customerTotalNetProfit = 0;
                        $customerTotalQuoteAmount = 0;
                        $customerTotalReceivedAmount = 0;
                        $customerTotalDueAmount = 0;
                        $customerTotalGross = 0;
                        foreach ($customerWiseData as $customerData) {
                            $gross = ($customerData['baseFare'] + $customerData['tax'] + $customerData['otherTax']);
                            $due = ($customerData['quoteAmount'] - $customerData['receivedAmount']);
                            ?>
                            <tr>
                                <td><?= $customerData['customer']['name'] ?></td>
                                <td><?= $customerData['total'] ?></td>
                                <td><?= $customerData['numberOfSegment'] ?></td>
                                <td><?= number_format($gross) ?></td>
                                <td><?= number_format($customerData['quoteAmount']) ?></td>
                                <td><?= number_format($customerData['receivedAmount']) ?></td>
                                <td><?= number_format($due) ?></td>
                                <td><?= number_format($customerData['netProfit']) ?></td>
                            </tr>
                            <?php
                            $customerTotalQty += $customerData['total'];
                            $customerTotalSegment += $customerData['numberOfSegment'];
                            $customerTotalQuoteAmount += $customerData['quoteAmount'];
                            $customerTotalReceivedAmount += $customerData['receivedAmount'];
                            $customerTotalNetProfit += $customerData['netProfit'];
                            $customerTotalDueAmount += $due;
                            $customerTotalGross += $gross;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr style="background-color: #8eae7f;">
                            <th>Total</th>
                            <th><?= $customerTotalQty ?></th>
                            <th><?= $customerTotalSegment ?></th>
                            <th><?= number_format($customerTotalGross) ?></th>
                            <th><?= number_format($customerTotalQuoteAmount) ?></th>
                            <th><?= number_format($customerTotalReceivedAmount) ?></th>
                            <th><?= number_format($customerTotalDueAmount) ?></th>
                            <th><?= number_format($customerTotalNetProfit) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>



