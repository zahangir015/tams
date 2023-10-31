<?php

use app\models\Agent;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tickets */
/* @var $form yii\widgets\ActiveForm */
$this->title = Yii::t('app', 'Monthly PL Report');
$this->params['breadcrumbs'][] = $this->title;
$grandIncomeExp = [];
?>
<div class="profit-loss-form">
    <div class="card card-custom mb-5">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Generate Profit Loss Report
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
                <div class="col-md" style="padding-top:30px; padding-right:0">
                    <?= Html::submitButton(Yii::t('app', 'Generate Report'), ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Reset', '/account/account-report/profit-loss', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<section class="invoice table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>PARTICULARS</th>
            <th>GROSS MARGINAL VALUE</th>
            <th>COST</th>
            <th>NET REVENUE</th>
        </tr>
        <tr style="background-color: lightgrey">
            <th>Flight Net Revenue</th>
            <th><?= number_format($data['Flight']['gross']) ?></th>
            <th><?= number_format($data['Flight']['totalCost']) ?></th>
            <th><?= number_format($data['Flight']['totalNetProfit']) ?></th>
        </tr>
        <tr style="background-color: lightgrey">
            <td colspan="3">VISA</td>
            <th><?= number_format($data['Visa']['totalQuote']) ?></th>
            <th><?= number_format($data['Visa']['totalCost']) ?></th>
            <th><?= number_format($data['Visa']['totalNetProfit']) ?></th>
        </tr>
        <tr style="background-color: lightgrey">
            <td colspan="3">Holiday</td>
            <th><?= number_format($data['Holiday']['totalQuote']) ?></th>
            <th><?= number_format($data['Holiday']['totalCost']) ?></th>
            <th><?= number_format($data['Holiday']['totalNetProfit']) ?></th>
        </tr>
        <tr style="background-color: lightgrey">
            <td colspan="3">HOTEL</td>
            <th><?= number_format($data['Hotel']['totalQuote']) ?></th>
            <th><?= number_format($data['Hotel']['totalCost']) ?></th>
            <th><?= number_format($data['Hotel']['totalNetProfit']) ?></th>
        </tr>
        <tr style="background-color: lightgrey">
            <th colspan="3">TOTAL</th>
            <th><?= number_format(($data['Hotel']['totalQuote'] + $data['Holiday']['totalQuote'] + $data['Visa']['totalQuote'] + $data['Flight']['gross'])) ?></th>
            <th><?= number_format(($data['Hotel']['totalCost'] + $data['Holiday']['totalCost'] + $data['Visa']['totalCost'] + $data['Flight']['totalCost'])) ?></th>
            <th><?= number_format(($data['Hotel']['totalNetProfit'] + $data['Holiday']['totalNetProfit'] + $data['Visa']['totalNetProfit'] + $data['Flight']['totalNetProfit'])) ?></th>
        </tr>
        <?php
        $months = array_keys($data);
        $grandExp = $categoryExp = [];
        foreach ($expenseData as $cat => $items) {
            ?>
            <tr style="background-color: lightgrey">
                <th colspan="3"><?= $cat ?></th>
                <?php
                $totalExpense = 0;
                foreach ($months as $month) {
                    echo "<td>".number_format($items['sum']->amount, 2)."</td>";
                    $totalExpense += $items['sum']->amount;
                }
                ?>
                <th><?= number_format($totalExpense, 2) ?></th>
            </tr>
            <?php
            foreach ($items as $subCat => $singleItem) {
                if ($subCat !== 'sum') {
                    ?>
                    <tr>
                        <td colspan="3"><?= $subCat ?></td>
                        <?php
                        $subCatSum = 0;
                        foreach ($months as $month) {
                            $subCatSum += $singleItem[$month]->amount;
                            echo "<td>" . number_format($singleItem[$month]->amount, 2) . "</td>";
                        }
                        ?>
                        <th><?= number_format($subCatSum, 2) ?></th>
                    </tr>
                    <?php
                }
            }
        }
        ?>
        <tr>
            <th colspan="3">NET PROFIT</th>
            <?php
            $grandEbitda = $grossRevSum = 0;
            foreach ($data as $key => $value) {
                $ait = ((double)$value['ticket']->baseFare + (double)$value['ticket']->tax + (double)$value['ticket']->otherTax) * 0.003;
                $costSum = ((double)$value['ticket']->baseFare + (double)$value['ticket']->tax + (double)$value['ticket']->otherTax + $value['ticket']->serviceCharge + $ait);
                $netCostSum = ($costSum - $value['ticket']->commissionReceived - $value['ticket']->incentiveReceived);

                $grossSum = ((double)$value['ticket']->baseFare + (double)$value['ticket']->tax + (double)$value['ticket']->otherTax + ($value['ticket']->refundData['quoteAmount'] - $value['ticket']->refundData['payToAgent']));
                $netRevenueSum = ($grossSum - $netCostSum);
                $discount = ($grossSum - $value['ticket']->quoteAmount);
                $ticketRevenueSum = ($netRevenueSum - $discount);
                $visaNetProfitSum = ((double)$value['visa']->quoteAmount - (double)$value['visa']->totalCostOfSale);
                $packageNetProfitSum = ((double)$value['Package']['quoteAmount'] - (double)$value['Package']['totalCostOfSale']);
                $hotelNetProfitSum = ((double)$value['Hotel']['quoteAmount'] - (double)$value['Hotel']['totalCostOfSale']);
                $revSum = ($ticketRevenueSum + $visaNetProfitSum + $packageNetProfitSum + $hotelNetProfitSum);

                $expense = isset($expenseSum[$key]) ? $expenseSum[$key] : 0;
                echo "<th>" . number_format(($revSum - $expense), 2) . "</th>";
                $grandEbitda += ($revSum - $expense);
            }
            ?>
            <th><?= number_format($grandEbitda, 2) ?></th>
        </tr>
    </table>
</section>