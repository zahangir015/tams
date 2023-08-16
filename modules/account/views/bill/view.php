<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Bill */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bill-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row">
        <div class="col-9">
            <div class="card">
                <div class="card-body">
                    <div class="row pb-5">
                        <div class="col-md">
                            <h4>
                                <img alt="Logo" height="60" width="150"
                                     src="<?= (isset($company) && isset($company->logo)) ? Url::to('/uploads/company/' . $company->logo) : '' ?>">  <?= isset($company) ? $company->name : '' ?>
                                <small class="float-right">Date: <?= $model->date ?></small>
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm invoice-col">
                            From
                            <address>
                                <strong><?= $model->supplier->company ?></strong><br>
                                <?= $model->supplier->address ?><br>
                                Phone: <?= $model->supplier->phone ?><br>
                                Email: <?= $model->supplier->email ?>
                            </address>
                        </div>

                        <div class="col-sm invoice-col">
                            To
                            <address>
                                <strong><?= $company->name ?></strong><br>
                                <?= $company->address ?><br>
                                Phone: <?= $company->phone ?><br>
                                Email: <?= $company->email ?>
                            </address>
                        </div>

                    </div>
                    <div class="row pb-5">
                        <div class="table-responsive border-bottom">
                            <h5>Bill Details</h5>
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
                                        <td>
                                            <?php
                                            $url = '/sale/' . Utilities::getServiceName($invoiceDetail->refModel) . '/view';
                                            echo Html::a('<i class="fa fa-info-circle"></i>', [$url, 'uid' => $invoiceDetail->service->uid],
                                                [
                                                    'title' => Yii::t('app', 'View More'),
                                                    'target' => '_blank',
                                                    'class' => 'btn btn-primary btn-xs',
                                                ]) ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>

                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($model->transactions)) : ?>
                        <div class="row">
                            <h5>Money Receipt</h5>
                            <div class="separator separator-dashed my-10"></div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Received Amount</th>
                                    <th>Payment Mode</th>
                                    <th>Payment Date</th>
                                    <th>Payment Charge</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="t-body">
                                <?php
                                foreach ($model->transactions as $key => $transaction) {
                                    ?>
                                    <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= $transaction->paidAmount ?></td>
                                        <td><?= $transaction->paymentMode ?></td>
                                        <td><?= $transaction->paymentDate ?></td>
                                        <td><?= $transaction->paymentCharge ?></td>
                                        <td><?= Html::a('<i class="fa fa-money-bill-alt"></i>',
                                                ['money-receipt', 'uid' => $transaction->uid],
                                                [
                                                    'title' => Yii::t('app', 'View Money Receipt'),
                                                    'class' => 'btn btn-primary btn-xs',
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
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <p class="lead">Details</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th>Invoice #:</th>
                                        <td><?= $model->invoiceNumber ?></td>
                                    </tr>
                                    <tr>
                                        <th>Due Date:</th>
                                        <td><?= date('l jS \of F Y', strtotime($model->expectedPaymentDate)) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Creator:</th>
                                        <td><?= $model->createdBy ?></td>
                                    </tr>
                                    <tr>
                                        <th>Updater:</th>
                                        <td><?= $model->updatedBy ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12">
                            <p class="lead">Amount Due</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th style="width:50%">Subtotal:</th>
                                        <td><?= number_format($model->paidAmount + $model->dueAmount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paid:</th>
                                        <td><?= number_format($model->paidAmount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Due:</th>
                                        <td><?= number_format($model->dueAmount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Discounted:</th>
                                        <td><?= number_format($model->discountedAmount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Refund Adjustment Amount:</th>
                                        <td><?= number_format($model->refundAdjustmentAmount) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12">
                            <?php
                            if ($model->dueAmount != 0) {
                                echo Html::a('<i class="far fa-credit-card"></i> Payment', ['pay', 'uid' => $model->uid], [
                                    'title' => 'pay', 'class' => 'btn btn-success float-right',
                                ]);
                            }
                            ?>
                            <?= Html::a('<i class="fa fa-envelope-open"></i> Send to Customer', ['send', 'uid' => $model->uid], [
                                'title' => 'send', 'class' => 'btn btn-primary float-right',
                            ]); ?>
                            <?= Html::a('<i class="fas fa-print"></i> Preview Invoice Mail', ['preview', 'uid' => $model->uid], [
                                'title' => 'preview',
                                'class' => 'btn btn-default float-right',
                                ''
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uid',
            'supplierId',
            'billNumber',
            'date',
            'paidAmount',
            'dueAmount',
            'discountedAmount',
            'refundAdjustmentAmount',
            'remarks:ntext',
            'status',
            'createdBy',
            'createdAt',
            'updatedBy',
            'updatedAt',
        ],
    ]) ?>

</div>
