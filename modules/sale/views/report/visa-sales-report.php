<?php

use app\components\Constant;
use app\components\GlobalConstant;
use app\modules\sales\models\FlightAirport;
use app\modules\sales\components\TicketComponent;
use app\modules\sales\services\ReportService;
use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\data\ArrayDataProvider;
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
                    <?= Html::a('Reset', '/sale/report/visa-sales-report', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if (in_array("COUNTRY", $getReportType)) { ?>
        <div class="table-responsive">
            <?php
            $dataProvider = new ArrayDataProvider([
                'allModels' => $countryWiseData,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => [
                        'country',
                        'qty',
                        'totalQuote',
                        'totalReceived',
                        'totalDue',
                        'netProfit',
                    ],
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'country',
                    [
                        'attribute' => 'qty',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalQuote',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalReceived',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalDue',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'netProfit',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                ],
                'toolbar' => [
                    '{export}',
                    '{toggleData}'
                ],
                'pjax' => true,
                'bordered' => true,
                'striped' => false,
                'condensed' => false,
                'responsive' => true,
                'hover' => true,
                'showFooter' => true,
                'showPageSummary' => true,
                'panel' => [
                    'heading' => "Customer Category Wise Report - ".$date,
                    'type' => GridView::TYPE_INFO
                ],
            ])
            ?>
        </div>
    <?php } ?>
    <?php if (in_array("CUSTOMER_CATEGORY", $getReportType)) { ?>
        <div class="table-responsive">
            <?php
            $dataProvider = new ArrayDataProvider([
                'allModels' => $customerCategoryWiseData,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => [
                        'category',
                        'qty',
                        'totalQuote',
                        'totalReceived',
                        'totalDue',
                        'netProfit',
                    ],
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'category',
                    [
                        'attribute' => 'qty',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalQuote',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalReceived',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalDue',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'netProfit',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                ],
                'toolbar' => [
                    '{export}',
                    '{toggleData}'
                ],
                'pjax' => true,
                'bordered' => true,
                'striped' => false,
                'condensed' => false,
                'responsive' => true,
                'hover' => true,
                'showFooter' => true,
                'showPageSummary' => true,
                'panel' => [
                    'heading' => "Customer Category Wise Report - ".$date,
                    'type' => GridView::TYPE_INFO
                ],
            ])
            ?>
        </div>
    <?php } ?>
    <?php if (in_array("BOOKING_TYPE", $getReportType)) { ?>
        <div class="table-responsive">
            <?php
            $dataProvider = new ArrayDataProvider([
                'allModels' => $bookingTypeWiseData,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => [
                        'bookingType',
                        'qty',
                        'totalQuote',
                        'totalReceived',
                        'totalDue',
                        'netProfit',
                    ],
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'bookingType',
                    [
                        'attribute' => 'qty',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalQuote',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalReceived',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalDue',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'netProfit',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                ],
                'toolbar' => [
                    '{export}',
                    '{toggleData}'
                ],
                'pjax' => true,
                'bordered' => true,
                'striped' => false,
                'condensed' => false,
                'responsive' => true,
                'hover' => true,
                'showFooter' => true,
                'showPageSummary' => true,
                'panel' => [
                    'heading' => "Booking Type Wise Report - ".$date,
                    'type' => GridView::TYPE_INFO
                ],
            ])
            ?>
        </div>
    <?php } ?>
    <?php if (in_array("CUSTOMER_CATEGORY_BOOKING_TYPE", $getReportType)) { ?>
        <div class="table-responsive">
            <?php
            $dataProvider = new ArrayDataProvider([
                'allModels' => $customerCategoryBookingTypeWiseData,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => [
                        'name',
                        'qty',
                        'totalQuote',
                        'totalReceived',
                        'totalDue',
                        'netProfit',
                    ],
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'name',
                    [
                        'attribute' => 'qty',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalQuote',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalReceived',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalDue',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'netProfit',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                ],
                'toolbar' => [
                    '{export}',
                    '{toggleData}'
                ],
                'pjax' => true,
                'bordered' => true,
                'striped' => false,
                'condensed' => false,
                'responsive' => true,
                'hover' => true,
                'showFooter' => true,
                'showPageSummary' => true,
                'panel' => [
                    'heading' => "Booking Type & Customer category Wise Report - ".$date,
                    'type' => GridView::TYPE_INFO
                ],
            ])
            ?>
        </div>
    <?php } ?>
    <?php if (in_array("CUSTOMER", $getReportType)) { ?>
        <div class="table-responsive">
            <?php
            $dataProvider = new ArrayDataProvider([
                'allModels' => $customerWiseData,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => [
                        'name',
                        'qty',
                        'totalQuote',
                        'totalReceived',
                        'totalDue',
                        'netProfit',
                    ],
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'name',
                    [
                        'attribute' => 'qty',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalQuote',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalReceived',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalDue',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'netProfit',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                ],
                'toolbar' => [
                    '{export}',
                    '{toggleData}'
                ],
                'pjax' => true,
                'bordered' => true,
                'striped' => false,
                'condensed' => false,
                'responsive' => true,
                'hover' => true,
                'showFooter' => true,
                'showPageSummary' => true,
                'panel' => [
                    'heading' => "Customer Wise Report - ".$date,
                    'type' => GridView::TYPE_INFO
                ],
            ])
            ?>
        </div>
    <?php } ?>
    <?php if (in_array("SUPPLIER", $getReportType)) { ?>
        <div class="table-responsive">
            <?php
            $dataProvider = new ArrayDataProvider([
                'allModels' => $supplierWiseData,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => [
                        'name',
                        'qty',
                        'totalQuote',
                        'totalReceived',
                        'totalDue',
                        'netProfit',
                    ],
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'name',
                    [
                        'attribute' => 'qty',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalQuote',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalReceived',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'totalDue',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'attribute' => 'netProfit',
                        'format' => ['decimal', 2],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                ],
                'toolbar' => [
                    '{export}',
                    '{toggleData}'
                ],
                'pjax' => true,
                'bordered' => true,
                'striped' => false,
                'condensed' => false,
                'responsive' => true,
                'hover' => true,
                'showFooter' => true,
                'showPageSummary' => true,
                'panel' => [
                    'heading' => "Supplier Wise Report - ".$date,
                    'type' => GridView::TYPE_INFO
                ],
            ])
            ?>
        </div>
    <?php } ?>
</div>



