<?php

use app\components\Constant;
use app\components\Utils;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\ticket\Ticket;
use yii\bootstrap4\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Ticket */

$this->title = $model->eTicket;
if ($model->type == ServiceConstant::TICKET_TYPE_FOR_REFUND['Refund']) {
    if ($model->ticketRefund->refundType == ServiceConstant::REFUND_TYPE['VOID']) {
        $label = Yii::t('app', 'Void List');
        $url = 'void-list';
    } else {
        $label = Yii::t('app', 'Refund List');
        $url = 'refund-list';
    }
} else {
    $label = Yii::t('app', 'Ticket List');
    $url = 'index';
}

$this->params['breadcrumbs'][] = ['label' => $label, 'url' => [$url]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="ticket-view">
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-toolbar float-right">
                <ul class="nav nav-light-primary nav-bold nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_4_1">
                            <span class="nav-icon"><i class="flaticon2-document"></i></span>
                            <span class="nav-text">Summary</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4_2">
                            <span class="nav-icon"><i class="flaticon2-information"></i></span>
                            <span class="nav-text">Details</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4_3">
                            <span class="nav-icon"><i class="flaticon2-information"></i></span>
                            <span class="nav-text">History</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="true" aria-expanded="false">
                            <span class="nav-icon"><i class="flaticon2-gear"></i></span>
                            <span class="nav-text">Actions</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <?= Html::a(Yii::t('app', '<span class="nav-icon"><i class="flaticon2-edit"></i> </span> <span class="nav-text">&nbsp;Edit</span>'), ['update', 'uid' => $model->uid], ['class' => 'dropdown-item']) ?>
                            <?= Html::a(Yii::t('app', '<span class="nav-icon"><i class="flaticon2-refresh"></i> </span> <span class="nav-text">&nbsp;Refund</span>'), ['refund', 'uid' => $model->uid], ['class' => 'dropdown-item']) ?>
                            <?= Html::a(Yii::t('app', '<span class="nav-icon"><i class="flaticon2-trash"></i></span> <span class="nav-text">&nbsp;Delete</span>'), ['delete', 'uid' => $model->uid], [
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
                                <th>Customer Category</th>
                                <th>E-Ticket</th>
                                <th>Parent Ticket</th>
                                <th>PNR Code</th>
                                <th>Type</th>
                                <?php
                                if ($model->type == ServiceConstant::TICKET_TYPE_FOR_REFUND['Refund']) {
                                    ?>
                                    <th>Refund Request Date</th>
                                    <th>Refund Status</th>
                                    <th>Refund Medium</th>
                                    <th>Refund Method</th>
                                    <?php
                                }
                                ?>
                            </tr>
                            <tr>
                                <td><?= $model->issueDate ?></td>
                                <td><?= $model->customer->name ?></td>
                                <td><?= $model->customerCategory ?></td>
                                <td><?= $model->eTicket ?></td>
                                <td><?= ($model->motherTicket) ? $model->motherTicket->eTicket : '' ?></td>
                                <td><?= $model->pnrCode ?></td>
                                <td>
                                    <?= $model->type ?>
                                </td>
                                <?php
                                if ($model->type == ServiceConstant::TICKET_TYPE_FOR_REFUND['Refund']) {
                                    ?>
                                    <td><?= $model->refundRequestDate ?></td>
                                    <td><?= $model->ticketRefund->refundStatus ?></td>
                                    <td><?= $model->ticketRefund->refundMedium ?></td>
                                    <td><?= $model->ticketRefund->refundMethod ?></td>
                                    <?php
                                }
                                ?>
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
                                <th>Supplier Company</th>
                                <th>Cost of Sale</th>
                                <th>Supplier Payment Status</th>
                                <th>Paid Amount</th>
                                <th>Type</th>
                            </tr>

                            <tr>
                                <td><?= $model->ticketSupplier->supplier->name ?></td>
                                <td><?= $model->ticketSupplier->supplier->company ?></td>
                                <td>BDT <?= number_format($model->ticketSupplier->costOfSale) ?></td>
                                <td>
                                    <?= $model->ticketSupplier->paymentStatus ?>
                                </td>
                                <td>BDT <?= number_format($model->ticketSupplier->paidAmount) ?></td>
                                <td>
                                    <?= $model->ticketSupplier->type ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    if (isset($model->ticketRefund)) {
                        ?>
                        <div class="col-md-12">
                            <p class="lead">Refund Details</p>
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>Refund Request Date</th>
                                    <th>Refund Status</th>
                                    <th>Refund Type</th>
                                    <th>Refund Medium</th>
                                    <th>Refund Method</th>
                                    <th>Supplier Charge</th>
                                    <th>Airline Charge</th>
                                    <th>Service Charge</th>
                                    <th>Is Refunded</th>
                                    <th>Refund Date</th>
                                    <th>Refund Amount</th>
                                </tr>

                                <tr>
                                    <td><?= $model->ticketRefund->refundRequestDate ?></td>
                                    <td><?= $model->ticketRefund->refundStatus ?></td>
                                    <td><?= $model->ticketRefund->refundType ?></td>
                                    <td><?= $model->ticketRefund->refundMedium ?></td>
                                    <td><?= $model->ticketRefund->refundMethod ?></td>
                                    <td>BDT <?= number_format($model->ticketRefund->supplierRefundCharge) ?></td>
                                    <td>BDT <?= number_format($model->ticketRefund->airlineRefundCharge) ?></td>
                                    <td><?= $model->ticketRefund->serviceCharge ?></td>
                                    <td><?= $model->ticketRefund->isRefunded ?></td>
                                    <td><?= $model->ticketRefund->refundDate ?></td>
                                    <td>
                                        BDT <?= ($model->ticketRefund->refundedAmount) ? number_format($model->ticketRefund->refundedAmount) : 0 ?></td>
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
                                <th>Payment Status</th>
                                <th>Received Amount</th>
                            </tr>
                            <tr>
                                <td>BDT <?= number_format($model->costOfSale) ?></td>
                                <td>BDT <?= number_format($model->quoteAmount) ?></td>
                                <td>
                                    <?= $model->paymentStatus ?>
                                </td>
                                <td>BDT <?= number_format($model->receivedAmount) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_4_2" role="tabpanel" aria-labelledby="kt_tab_pane_4_2">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'customerId',
                                'label' => 'Customer',
                                'value' => function ($model) {
                                    return $model->customer->name;
                                }
                            ],
                            [
                                'attribute' => 'airlineId',
                                'label' => 'Airline',
                                'value' => function ($model) {
                                    return $model->airline->name;
                                }
                            ],
                            'paxName',
                            'paxType',
                            'eTicket',
                            [
                                'attribute' => 'issueDate',
                                'format' => 'date'
                            ],
                            [
                                'attribute' => 'departureDate',
                                'format' => 'date'
                            ],
                            'refundRequestDate',
                            'bookingCode',
                            'pnrCode',
                            'routing',
                            'numberOfSegment',
                            'type',
                            'CodeShare',
                            'tripType',
                            'baseFare',
                            'tax',
                            'otherTax',
                            'commission',
                            'incentive',
                            'commissionReceived',
                            'incentiveReceived',
                            'paymentStatus',
                            'quoteAmount',
                            'govtTax',
                            'costOfSale',
                            'netProfit',
                            'reference',
                            'invoiceId',
                            [
                                'attribute' => 'bookedOnline',
                                'value' => function ($model) {
                                    return $model->bookedOnline ? '<span class="label label-lg label-light-success label-inline">yes</span>' : '<span class="label label-lg label-light-primary label-inline">No</span>';
                                },
                                'format' => 'html',
                            ],
                            'GDS',
                            'flightType',
                            'baggage',
                            'accountsNote:ntext',
                            'receivedAmount',
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    return $model->status ? '<span class="label label-lg label-light-primary label-inline">Active</span>' : '<span class="label label-lg label-light-danger label-inline">Inactive</span>';
                                },
                                'format' => 'html',
                            ],
                            'createdAt',
                            'updatedAt',
                            'createdBy',
                            'updatedBy',
                        ],
                    ]) ?>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_4_3" role="tabpanel" aria-labelledby="kt_tab_pane_4_3">
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
