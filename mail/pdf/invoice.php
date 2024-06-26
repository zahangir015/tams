<?php

use app\modules\sale\components\ServiceConstant;
use yii\helpers\Url;

$logo = 'http://mytrams.com/uploads/company/'.$company->logo;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice <?= $invoice->invoiceNumber ?></title>
</head>
<body style="margin: 0; padding: 0;font-family: Helvetica,Arial,sans-serif;">
<div style="width: 100%; margin: auto; background: #ffffff;">
    <div style="width: 100%; margin: auto; background: white; font-family: 'Helvetica','Arial',sans-serif; color: #585858;">
        <div style="text-align: center; height: auto;">
            <img src="<?= $logo ?>" alt="" style="width: 100%; height: 100px; max-width: 220px">
            <h4><?= ($invoice->paidAmount == 0) ? 'Unpaid Payment Update!' : (($invoice->paidAmount && $invoice->dueAmount) ? 'Partial Payment Update!' : 'Your Payment has been Received!') ?></h4>
        </div>
        <div>
            <h3 style="color: #000000; text-align: left; font-size: 16px; font-weight: 500">
                INVOICE:&nbsp;<?= $invoice->invoiceNumber ?>
            </h3>
            <div style="width: 50%; float: left; position: relative; text-align: left; font-size: 15px;color: #000000;">
                <p>
                    <span>Customer Details</span><br>
                    <strong><?= $invoice->customer->category == ServiceConstant::CUSTOMER_CATEGORY['B2C'] ? $invoice->customer->name : $invoice->customer->company ?></strong><br>
                    <strong><?= $invoice->customer->address ?></strong>
                </p>
            </div>
            <div style="width: 45%; float: left; position: relative; text-align: left; font-size: 15px;color: #000000;">
                <p>
                    Date:<br>
                    <strong><?= date('j F Y', strtotime($invoice->date)) ?></strong><br>
                    Payment Due Date:<br>
                    <strong><?= ($invoice->expectedPaymentDate) ? date('j F Y', strtotime($invoice->expectedPaymentDate)) : '' ?> </strong>
                </p>
            </div>
            <div style=" clear: both;"></div>
            <div style="background: #ffffff; margin-top: 8px; border-radius: 4px;">
                <table width="100%"
                       style="margin: auto; font-size: 15px; border: none !important; border-collapse: collapse">
                    <thead style="color: #0a0a0a; font-weight: 500; text-align: left; background-color: #e0e0e1; padding: .75rem;">
                    <tr>
                        <th style="padding: .75rem;">Service Type</th>
                        <th style="padding: .75rem;">Identification #</th>
                        <th style="padding: .75rem;">Issue</th>
                        <th style="padding: .75rem;">Pax Name</th>
                        <th style="padding: .75rem;">Route/Details</th>
                        <th style="padding: .75rem; text-align: right">paid Amount</th>
                        <th style="padding: .75rem; text-align: right">Due Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($invoice->details as $key => $invoiceDetail) {
                        $service = $invoiceDetail->refModel::findOne(['id' => $invoiceDetail->refId]);
                        if ($invoiceDetail->service->formName() == 'Ticket') {
                            $serviceDetails = $service->route;
                            $identificationNumber = $service->eTicket;
                            $paxName = $service->paxName;
                        } else {
                            $supplierModel = strtolower($service->formName()) . 'Suppliers';
                            $serviceDetails = implode('.', array_column($service->$supplierModel, 'serviceDetails'));
                            $identificationNumber = $service->identificationNumber;
                            $paxName = $invoice->customer->name;
                        }
                    ?>
                    <tr>
                        <td style="padding: .75rem;text-align: left;"><?= $service->formName() ?></td>
                        <td style="padding: .75rem;text-align: left;"><?= $identificationNumber ?></td>
                        <td style="padding: .75rem; text-align: left;"><?= $service->issueDate ?></td>
                        <td style="padding: .75rem; text-align: left;"><?= $paxName ?></td>
                        <td style="padding: .75rem; text-align: left;"><?= $serviceDetails ?></td>
                        <td style="padding: .75rem; text-align: right;"><?= number_format($invoiceDetail->paidAmount) ?></td>
                        <td style="padding: .75rem; text-align: right;"><?= number_format($invoiceDetail->dueAmount) ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 10px;">
                <table width="100%"
                       style="margin: auto; font-size: 16px; border: none !important; text-align: left; border-collapse: collapse">
                    <tfoot style="background-color: #E3F0FF; color: #0a0a0a; text-align: left; font-weight: 500;font-size: 14px;">
                    <tr>
                        <td width="80%" style="padding: .75rem;">
                            Balance Due
                        </td>
                        <td width="20%" style="padding: .75rem; text-align: right">
                            BDT <?= number_format($invoice->dueAmount) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="80%" style="padding: .75rem;">
                            Total Paid
                        </td>
                        <td width="20%" style="padding: .75rem; text-align: right">
                            BDT <?= number_format($invoice->paidAmount) ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div style="clear: both"></div>
        <div style="background: #EFEFF4; box-sizing: border-box; padding: 10px; padding-bottom: 25px; text-align: left; width: 100%; color: #474749;line-height: 0.5; font-size: 15px; margin-top: 10px; text-align: center">
            <p>
                Contact Us:
                Address: <?= $company->address ?>
                <a href="mailto:<?= $company->email ?>" style="color:#2A8CFF;font-weight:500;line-height:0.6;text-align:left;text-decoration:none;"><?= $company->email ?></a>
                |
                <a href="tel:<?= $company->phone ?>" style="color:#2A8CFF;font-weight:500;line-height:0.6;text-align:left;text-decoration:none;"><?= $company->phone ?></a>
            </p>
        </div>
    </div>
</div>
</body>
</html>