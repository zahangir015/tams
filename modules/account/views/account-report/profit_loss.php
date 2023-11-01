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
            <th>NET REVENUE</th>
        </tr>
        <tr style="background-color: lightgrey">
            <th>Flight Net Revenue</th>
            <th><?= $data['Flight']['totalNetProfit'] ?></th>
        </tr>
        <tr style="background-color: lightgrey">
            <td>Visa Net Revenue</td>
            <th><?= $data['Visa']['totalNetProfit'] ?></th>
        </tr>
        <tr style="background-color: lightgrey">
            <td>Holiday Net Revenue</td>
            <th><?= $data['Holiday']['totalNetProfit'] ?></th>
        </tr>
        <tr style="background-color: lightgrey">
            <td>HOTEL Net Revenue</td>
            <th><?= $data['Hotel']['totalNetProfit'] ?></th>
        </tr>
        <tr style="background-color: lightgrey">
            <th>TOTAL SALES REVENUE</th>
            <th><?= ($data['Hotel']['totalNetProfit'] + $data['Holiday']['totalNetProfit'] + $data['Visa']['totalNetProfit'] + $data['Flight']['totalNetProfit']) ?></th>
        </tr>
        <?php
        $grandExp = $categoryExp = [];
        foreach ($expenseData as $category => $subcategory) {
            ?>
            <tr style="background-color: lightgrey">
                <th><?= $category ?></th>
                <th><?= $categoryExpenseSum[$category]['sum'] ?></th>
            </tr>
            <?php
            foreach ($subcategory as $key => $expense) {
                ?>
                <tr>
                    <td><?= $expense->subCategory->name ?></td>
                    <td><?= $expense->totalCost ?></td>
                </tr>
                <?php
            }
        }
        ?>
        <tr>
            <th>Total EXPENSE</th>
            <td><?= $expenseSum ?></td>
        </tr>
        <tr>
            <th>TOTAL REVENUE</th>
            <th><?= (($data['Hotel']['totalNetProfit'] + $data['Holiday']['totalNetProfit'] + $data['Visa']['totalNetProfit'] + $data['Flight']['totalNetProfit']) - $expenseSum) ?></th>
        </tr>
    </table>
</section>