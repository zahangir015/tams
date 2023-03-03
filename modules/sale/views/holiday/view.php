<?php

use app\modules\sale\components\ServiceConstant;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\holiday\Holiday */

$this->title = $model->identificationNumber;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Holidays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="holiday-view">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-toolbar float-right">
                <ul class="nav nav-light-primary nav-bold nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_4_1"> Details </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4_2"> Summary</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4_3"> History</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="true" aria-expanded="false"> Actions
                        </a>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <?= Html::a(Yii::t('app', '<i class="fa fa-edit"></i> Update'), [($model->type == ServiceConstant::TYPE['Refund']) ? 'refund-update' : 'update', 'uid' => $model->uid], ['class' => 'dropdown-item']) ?>
                            <?php
                            if ($model->type != ServiceConstant::TYPE['Refund']) {
                                echo Html::a(Yii::t('app', '<i class="fa fa-minus-circle"></i> Refund'), ['refund', 'uid' => $model->uid], ['class' => 'dropdown-item']);
                            }
                            ?>

                            <?= Html::a(Yii::t('app', '<i class="fa fa-trash-alt"></i> Delete'), ['delete', 'uid' => $model->uid], [
                                'class' => 'dropdown-item',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_tab_pane_4_1" role="tabpanel"
                     aria-labelledby="kt_tab_pane_4_1">
                    <div class="col-md-12">
                        <p class="lead">Basic Details</p>
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>Issue Date</th>
                                <th>Customer Name</th>
                                <th>Category</th>
                                <th>Quote Amount</th>
                                <th>Received Amount</th>
                                <th>Payment Status</th>
                                <th>Type</th>
                            </tr>
                            <tr>
                                <td><?= $model->issueDate ?></td>
                                <td><?= $model->customer->name ?></td>
                                <td><?= $model->customerCategory ?></td>
                                <td><?= $model->quoteAmount ?></td>
                                <td><?= $model->receivedAmount ?></td>
                                <td><?= $model->paymentStatus ?></td>
                                <td><?= $model->type ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <p class="lead">Suppliers Details</p>
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Supplier Reference</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Cost of Sale</th>
                                <th>Paid Amount</th>
                                <th>Payment Status</th>
                                <th>Type</th>
                                <th>Accounts Note</th>
                            </tr>

                            <?php
                            foreach ($model->holidaySuppliers as $single) {
                                ?>
                                <tr>
                                    <td><?= $single->supplier->company ?></td>
                                    <td><?= $single->supplierRef ?></td>
                                    <td><?= $single->quantity ?></td>
                                    <td>BDT <?= number_format($single->unitPrice) ?></td>
                                    <td>BDT <?= number_format($single->costOfSale) ?></td>
                                    <td>BDT <?= number_format($single->paidAmount) ?></td>
                                    <td><?= $single->paymentStatus ?></td>
                                    <td><?= $single->type ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    if (isset($model->holidayRefund)) {
                        ?>
                        <div class="col-md-12">
                            <p class="lead">Refund Details</p>
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>Refund Request Date</th>
                                    <th>Refund Status</th>
                                    <th>Refund Medium</th>
                                    <th>Refund Method</th>
                                    <th>Supplier Charge</th>
                                    <th>Service Charge</th>
                                    <th>Is Refunded</th>
                                    <th>Refund Date</th>
                                    <th>Refund Amount</th>
                                </tr>

                                <tr>
                                    <td><?= $model->holidayRefund->refundRequestDate ?></td>
                                    <td><?= $model->holidayRefund->refundStatus ?></td>
                                    <td><?= $model->holidayRefund->refundMedium ?></td>
                                    <td><?= $model->holidayRefund->refundMethod ?></td>
                                    <td>BDT <?= $model->holidayRefund->supplierRefundCharge ? number_format($model->holidayRefund->supplierRefundCharge) : null ?></td>
                                    <td><?= $model->holidayRefund->serviceCharge ?></td>
                                    <td><?= $model->holidayRefund->isRefunded ?></td>
                                    <td><?= $model->holidayRefund->refundDate ?></td>
                                    <td>
                                        BDT <?= ($model->holidayRefund->refundedAmount) ? number_format($model->holidayRefund->refundedAmount) : 0 ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-md-12">
                        <p class="lead">Payment Details</p>
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>Total Cost of Sale</th>
                                <th>Quote Amount</th>
                                <th>Received Amount</th>
                                <th>Payment Status</th>
                            </tr>
                            <tr>
                                <td>BDT <?= number_format($model->costOfSale) ?></td>
                                <td>BDT <?= number_format($model->quoteAmount) ?></td>
                                <td>BDT <?= number_format($model->receivedAmount) ?></td>
                                <td>
                                    <?= $model->paymentStatus ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_4_2" role="tabpanel" aria-labelledby="kt_tab_pane_4_2">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'motherId',
                            'invoiceId',
                            'holidayCategoryId',
                            'identificationNumber',
                            'customerId',
                            'customerCategory',
                            'type',
                            'issueDate',
                            'departureDate',
                            'refundRequestDate',
                            'quoteAmount',
                            'costOfSale',
                            'netProfit',
                            'receivedAmount',
                            'paymentStatus',
                            'isOnlineBooked',
                            'route',
                            'status',
                            'createdBy',
                            'createdAt',
                            'updatedBy',
                            'updatedAt',
                        ],
                    ]) ?>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_4_3" role="tabpanel" aria-labelledby="kt_tab_pane_4_3">
                    <div class="serviceWrapper">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>Action</th>
                                <th>Creator</th>
                                <th>Old Data</th>
                                <th>Created At</th>
                            </tr>

                            <?php
                            foreach ($histories as $history) {
                                ?>
                                <tr>
                                    <td><?= $history->action ?></td>
                                    <td><?= $history->userId ?></td>
                                    <td><?= $history->tableData ?></td>
                                    <td><?= $history->snapshot ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
