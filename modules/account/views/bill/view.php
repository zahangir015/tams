<?php

use app\components\Utilities;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Bill */

$this->title = $model->billNumber;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bill-view">
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
                                    <th>Cost</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="t-body">
                                <?php foreach ($model->details as $billDetail) {
                                    $service = $billDetail->refModel::findOne(['id' => $billDetail->refId]);
                                    if (!$service) {
                                        continue;
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $service->formName() ?></td>
                                        <td><?= $billDetail->getIdentificationNumber($service) ?></td>
                                        <td><?= $service->type ?></td>
                                        <td><?= $service->issueDate ?></td>
                                        <td><?= $service->costOfSale ?></td>
                                        <td><?= $service->paidAmount ?></td>
                                        <td><?= $billDetail->dueAmount ?></td>
                                        <td>
                                            <?php
                                            $url = '/sale/' . Utilities::getServiceName($billDetail->refModel) . '/view';
                                            echo Html::a('<i class="fa fa-info-circle"></i>', [$url, 'uid' => $service->uid],
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
                                                ]) ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                    endif; ?>

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
                                        <th>Bill #:</th>
                                        <td><?= $model->billNumber ?></td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
