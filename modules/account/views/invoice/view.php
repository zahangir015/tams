<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Invoice */

$this->title = $model->invoiceNumber;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="invoice-view">

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <div class="row">
                        <div class="col-md">
                            <a href="#">
                                <img alt="Logo" height="60" width="150" src="">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12 mt-10">
                        <h4>Invoice# <?= $model->invoiceNumber ?></h4>
                    </div>
                    <div class="row p-5">
                        <div class="col-md-6">
                            <div class="font-weight-bold fs-7 text-gray-600 mb-1">Issue Date:</div>
                            <div class="font-weight-bolder fs-6 text-gray-800"><?= date('jS \of F Y', strtotime($model->createdAt)) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="font-weight-bolder fs-7 text-gray-600 mb-1">Due Date:</div>
                            <div class="font-weight-bolder fs-6 text-gray-800">
                                <span class="pe-2"><?= date('l jS \of F Y', strtotime($model->expectedPaymentDate)) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row p-5">
                        <div class="col-md">
                            <label class="control-label font-weight-bold" for="customer">Issue For:</label>
                            <p class="text-black well well-sm no-shadow" style="margin-top: 10px;">
                                <span style="font-weight: bold">Name: </span> <?= $model->customer->name ?><br>
                                <span style="font-weight: bold">Email: </span> <?= $model->customer->email ?><br>
                                <span style="font-weight: bold">Phone: </span> <?= $model->customer->phone ?><br>
                            </p>
                        </div>
                        <div class="col-md">
                            <label class="control-label font-weight-bold" for="dateRange">Issued By:</label>
                            <p class="text-black well well-sm no-shadow" style="margin-top: 10px;">
                                <span style="font-weight: bold">Due date: </span><?= date('l jS \of F Y', strtotime($model->expectedPaymentDate)) ?>
                                <br>
                                <span style="font-weight: bold">Created By: </span><?= $model->createdBy ?><br>
                                <span style="font-weight: bold">Received By: </span><?= $model->updatedBy ?>
                            </p>
                        </div>
                    </div>
                    <div class="row p-5">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <div class="table-responsive border-bottom mb-9">
                                    <h4>Invoice Details</h4>
                                    <div class="separator separator-dashed my-10"></div>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Identification#</th>
                                            <th>Type</th>
                                            <th>Issue</th>
                                            <th>Quote</th>
                                            <th>Received</th>
                                            <th>Payment Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="t-body">
                                        <?php foreach ($model->details as $invoiceDetail) {
                                            if (!$invoiceDetail->service) {
                                                continue;
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $invoiceDetail->service->formName() ?></td>
                                                <td><?= $invoiceDetail->getIdentificationNumber($invoiceDetail->service) ?></td>
                                                <td><?= $invoiceDetail->service->type ?></td>
                                                <td><?= $invoiceDetail->service->issueDate ?></td>
                                                <td><?= $invoiceDetail->service->quoteAmount ?></td>
                                                <td><?= $invoiceDetail->service->receivedAmount ?></td>
                                                <td><?= $invoiceDetail->service->paymentStatus ?></td>
                                                <td><?php
                                                    $url = '/sale/'. \app\components\Helper::getServiceName($invoiceDetail->refModel).'/view';
                                                    echo Html::a('<i class="fa fa-desktop"></i>', [$url, 'uid' => $invoiceDetail->service->uid],
                                                        [
                                                            'title' => Yii::t('app', 'View More'),
                                                            'class' => 'btn btn-default',
                                                            'target' => '_blank'
                                                        ]) ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php if (!empty($model->transactionStatement)) : ?>
                            <div class="col-md-12">
                                <h4>Money Receipt</h4>
                                <div class="separator separator-dashed my-10"></div>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Received Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Payment Date</th>
                                        <th>Payment Charge</th>
                                        <th>Discount</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="t-body">
                                    <?php
                                    foreach ($model->transactions as $key => $transaction) {
                                        ?>
                                        <tr>
                                            <td><?= ($key + 1) ?></td>
                                            <td><?= $transaction->amount ?></td>
                                            <td><?= $transaction->paymentMode ?></td>
                                            <td><?= $transaction->paymentDate ?></td>
                                            <td><?= $transaction->paymentCharge ?></td>
                                            <td><?= $transaction->discount ?></td>
                                            <td><?= Html::a('<i class="fa fa-money-bill-alt"></i>', ['money-receipt', 'uid' => $transaction->uid],
                                                    [
                                                        'title' => Yii::t('app', 'View Money Receipt'),
                                                        'class' => 'btn btn-default',
                                                        'target' => '_blank'
                                                    ]) ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        endif;?>
                    </div>
                    <?php if (!empty($refundTransactions['total_payable']) && !empty($refundTransactions['total_receivable'])) : ?>
                        <div class="col-md">
                            <div class="border" style="padding: 10px;">
                                <h4>Refund details</h4>
                                <hr>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td><h4>Total Payable:</h4></td>
                                        <td><h3 id="totalPayable">BDT <?= $refundTransactions['total_payable'] ?></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h4>Total Receiable:</h4></td>
                                        <td><h3 id="totalPayable">
                                                BDT <?= $refundTransactions['total_receivable'] ?></h3>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
