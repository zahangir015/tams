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
            <div class="row pb-5">
                <div class="col-md">
                    <h4>
                        <img alt="Logo" height="60" width="150" src=""> <?= $company->name ?>
                        <small class="float-right">Date: 2/10/2014</small>
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-sm invoice-col">
                    From
                    <address>
                        <strong><?= $company->name ?></strong><br>
                        <?= $company->address ?><br>
                        Phone: <?= $company->phone ?><br>
                        Email: <?= $company->email ?>
                    </address>
                </div>
                <div class="col-sm invoice-col">
                    To
                    <address>
                        <strong><?= $model->customer->company ?></strong><br>
                        <?= $model->customer->address ?><br>
                        Phone: <?= $model->customer->phone ?><br>
                        Email: <?= $model->customer->email ?>
                    </address>
                </div>
                <div class="col-sm invoice-col">
                    Details
                    <address>
                        <b>Invoice #<?= $model->invoiceNumber ?></b><br>
                        <b>Payment Due:</b> <?= date('l jS \of F Y', strtotime($model->expectedPaymentDate)) ?>
                        <br>
                        <b>Created By:</b> <?= $model->createdBy ?><br>
                        <b>Issue Date:</b> <?= $model->updatedBy ?><br>
                    </address>
                </div>
            </div>
            <div class="row pb-5">
                <div class="table-responsive border-bottom">
                    <h4>Invoice Details</h4>
                    <div class="separator separator-dashed my-10"></div>
                    <table class="table table-striped">
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
                                    $url = '/sale/' . \app\components\Helper::getServiceName($invoiceDetail->refModel) . '/view';
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
            <?php if (!empty($model->transactionStatement)) : ?>
                <div class="row">
                    <h4>Money Receipt</h4>
                    <div class="separator separator-dashed my-10"></div>
                    <table class="table table-striped">
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
            endif; ?>
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

            <div class="row">
                <div class="col-6">
                    <p class="lead">Payment Methods:</p>
                    <img src="https://adminlte.io/themes/v3/dist/img/credit/visa.png" alt="Visa">
                    <img src="https://adminlte.io/themes/v3/dist/img/credit/mastercard.png" alt="Mastercard">
                    <img src="https://adminlte.io/themes/v3/dist/img/credit/american-express.png" alt="American Express">
                    <img src="https://adminlte.io/themes/v3/dist/img/credit/paypal2.png" alt="Paypal">
                </div>

                <div class="col-6">
                    <p class="lead">Amount Due 2/22/2014</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th style="width:50%">Subtotal:</th>
                                <td>$250.30</td>
                            </tr>
                            <tr>
                                <th>Tax (9.3%)</th>
                                <td>$10.34</td>
                            </tr>
                            <tr>
                                <th>Shipping:</th>
                                <td>$5.80</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>$265.24</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="row no-print">
                <div class="col-12">
                    <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default float-right"><i class="fas fa-print"></i> Print</a>
                    <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                        Payment
                    </button>
                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                        <i class="fas fa-download"></i> Generate PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
