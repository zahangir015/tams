<?php

use app\components\GlobalConstant;
use app\components\Utilities;
use app\components\WidgetHelper;
use app\models\Attachment;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\web\JqueryAsset;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Invoice */
/* @var $form yii\bootstrap4\ActiveForm */

$this->registerJs(
    "var ajaxUrl = '" . Yii::$app->request->baseUrl . '/account/invoice/pending' . "';",
    View::POS_HEAD,
    'url'
);

$this->registerJsFile(
    '@web/js/invoice.js',
    ['depends' => [JqueryAsset::class]]
);
?>

<div class="invoice-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm invoice-col">
                            Details
                            <address>
                                <b>Client:</b> <?= $model->customer->company ?><br>
                                <b>Due Date:</b> <?= date('l jS \of F Y', strtotime($model->expectedPaymentDate)) ?><br>
                                <b>Created By:</b> <?= $model->createdBy ?><br>
                                <b>Issue Date:</b> <?= $model->updatedBy ?><br>
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive border-bottom mb-9">
                                <h4>Invoice Details</h4>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Identification#</th>
                                        <th>Type</th>
                                        <th>Issue</th>
                                        <th>Quote</th>
                                        <th>Received</th>
                                        <th>Due</th>
                                    </tr>
                                    </thead>
                                    <tbody id="t-body">
                                    <?php foreach ($model->details as $invoiceDetail) {
                                        $service = $invoiceDetail->refModel::findOne(['id' => $invoiceDetail->refId]);
                                        if (!$service) {
                                            continue;
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $service->formName() ?></td>
                                            <td><?= $invoiceDetail->getIdentificationNumber($service) ?></td>
                                            <td><?= $service->type ?></td>
                                            <td><?= $service->issueDate ?></td>
                                            <td><?= $service->quoteAmount ?></td>
                                            <td><?= $service->receivedAmount ?></td>
                                            <td><?= $invoiceDetail->dueAmount ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th>Paid Amount</th>
                                        <td><?= number_format($model->paidAmount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Due Amount</th>
                                        <td><?= number_format($model->dueAmount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Discounted Amount</th>
                                        <td><?= number_format($model->discountedAmount) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Refund Adjustment Amount</th>
                                        <td><?= number_format($model->refundAdjustmentAmount) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                        if (!empty($model->transaction)) {
                            ?>
                            <div class="col-12">
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
                                    foreach ($model->transaction as $key => $transaction) {
                                        ?>
                                        <tr>
                                            <td><?= ($key + 1) ?></td>
                                            <td><?= $transaction->amount ?></td>
                                            <td><?= $transaction->paymentMode ?></td>
                                            <td><?= $transaction->paymentDate ?></td>
                                            <td><?= $transaction->paymentCharge ?></td>
                                            <td><?= Html::a('', ['money-receipt', 'uid' => $transaction->uid],
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
                        }
                        $attachments = Attachment::findAll(['refModel' => 'app\modules\account\models\Invoice', 'refId' => $model->id]);
                        if (!empty($attachments)) {
                            ?>
                            <div class="col-12" id="files">
                                <h4>Uploaded Files</h4>
                                <div class="separator separator-dashed my-10"></div>
                                <?php
                                foreach ($attachments as $attachment) {
                                    ?>
                                    <div class="pull-left alert" style="display: inline-block">
                                        <?= Html::a('Download <i class="fas fa-download align-text-top"></i>', ['download', 'uid' => $attachment->uid], ['class' => 'btn btn-xs btn-info mb-2']); ?>
                                        <div>
                                            <?php
                                            $ext = pathinfo($attachment->name, PATHINFO_EXTENSION);
                                            switch ($ext) {
                                                case 'docx':
                                                case 'doc':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-word-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                case 'xls':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-excel-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                case 'ppt':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-powerpoint-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                case 'pdf':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-pdf-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                case 'zip':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-archive-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                case 'htm':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-code-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                case 'txt':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-text-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                case 'mov':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-movie-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                case 'mp3':
                                                    echo '<div style="width: 150px; height: 150px; text-align: center; border: 1px solid lightgrey; padding: 5px;"><i class="fa fa-file-audio-o fa-5x text-center"></i><span class="info-box-text" title="' . $attachment->name . '" style="margin-top: 10px;">' . $attachment->name . '</span></div>';
                                                    break;
                                                // note for these file types below no extension determination logic
                                                // has been configured (the keys itself will be used as extensions)
                                                case 'jpg':
                                                    echo '<a href="' . $attachment->name . '" target="_blank"><img src="' . $attachment->name . '" alt="" class="img-thumbnail" width="150px" style="height: 150px"/></a>';
                                                    break;
                                                case 'gif':
                                                    echo '<a href="' . $attachment->name . '" target="_blank"><img src="' . $attachment->name . '" alt="" class="img-thumbnail" width="150px" style="height: 150px"/></a>';
                                                    break;
                                                case 'png':
                                                    echo '<a href="' . $attachment->name . '" target="_blank"><img src="' . $attachment->name . '" alt="" class="img-thumbnail" width="150px" style="height: 150px"/></a>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        if (!empty($refundTransactions['total_payable']) && !empty($refundTransactions['total_receivable'])) : ?>
                            <div class="col-12 border" style="padding: 10px;">
                                <h4>Refund details</h4>
                                <hr>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td><h4>Total Payable:</h4></td>
                                        <td><h3 id="totalPayable">
                                                BDT <?= $refundTransactions['total_payable'] ?></h3>
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
                        <?php endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4>Payment Details</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($transaction, 'refundIds')->widget(Select2::classname(), [
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'data' => $refundList,
                                'options' => [
                                    'placeholder' => 'Select Refund Service ...',
                                    'multiple' => true,
                                    'id' => 'refundId'
                                ],
                                'pluginOptions' => [
                                    'tags' => true,
                                    'tokenSeparators' => [',', ' ']
                                ],
                            ])->label('Refund Adjustments'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($transaction, 'bankId')->widget(Select2::class, [
                                'theme' => Select2::THEME_DEFAULT,
                                'data' => $bankList,
                                'options' => [
                                    'id' => 'bankId',
                                    'class' => 'form-control',
                                    'placeholder' => 'Select a bank ...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Bank');
                            ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($transaction, 'reference')->textInput(['maxlength' => true])->label('Reference') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($transaction, 'paidAmount')->textInput(['value' => $model->dueAmount, 'max' => $model->dueAmount, 'min' => 1]) ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($transaction, 'paymentCharge')->textInput(['value' => 0]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <?= $form->field($transaction, 'paymentMode')->dropdownList(GlobalConstant::PAYMENT_MODE, ['prompt' => '']); ?>
                        </div>
                        <div class="col-md">
                            <?= $form->field($transaction, 'paymentDate')->widget(DatePicker::class, WidgetHelper::getDatewidget('paymentDate', 'paymentDate', false, true)); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div id="files" style="background-color: #FFFFFF; padding: 10px;">
                                <?= $form->field($model, 'invoiceFile[]')->widget(FileInput::class, [
                                    'options' => [
                                        'multiple' => true,
                                        'accept' => '*'
                                    ],
                                    'pluginOptions' => [
                                        'maxFileCount' => 10,
                                    ]
                                ])->label('Upload files');
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Pay'), ['class' => $model->isNewRecord ? 'btn btn-success float-right' : 'btn btn-primary float-right']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
