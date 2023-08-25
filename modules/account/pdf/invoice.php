<?php

use yii\helpers\Url;

$logo = $company ? Url::to('@web/uploads/company/').$company->logo : '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice <?= $invoice->invoiceNumber ?></title>
</head>
<body style="margin: 0; padding: 0;font-family: Helvetica,Arial,sans-serif;">
<div style="width: 100%; margin: auto; background: #ffffff; padding: 10px 0;">
    <div style="width: 100%; margin: auto; background: white; font-family: 'Helvetica','Arial',sans-serif; color: #585858;">
        <div style="text-align: center; height: auto; margin-top: 10px;">
            <img src="<?= $logo ?>" alt="" style="width: 100%; height: auto; max-width: 220px">
            <h4><?= ($invoice->paidAmount == 0) ? 'Unpaid Payment Update!' : (($invoice->paidAmount && $invoice->dueAmount) ? 'Partial Payment Update!' : 'Your Payment has been Received!') ?></h4>
        </div>
        <div>
            <h3 style="color: #000000; text-align: left; font-size: 16px; font-weight: 500">
                INVOICE:&nbsp;<?= $invoice->invoiceNumber ?>
            </h3>
            <div style="width: 50%; float: left; position: relative; text-align: left; font-size: 15px;color: #000000;">
                <p>
                    <span>Customer Details</span><br>
                    <strong><?= $invoice->customer->name ?></strong><br>
                    <strong><?= $invoice->customer->address ?></strong>
                </p>
            </div>
            <div style="width: 45%; float: left; position: relative; text-align: left; font-size: 15px;color: #000000;">
                <p>
                    Date:<br>
                    <strong><?= date('j F Y', strtotime($invoice->createdAt)) ?></strong><br>
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
                    $supplierModel = strtolower($invoiceDetail->service->formName()) . 'Suppliers';
                    if ($invoiceDetail->service->formName() == 'Ticket') {
                        $serviceDetails = $invoiceDetail->service->route;
                        $identificationNumber = $invoiceDetail->service->eTicket;
                        $paxName = $invoiceDetail->service->paxName;
                    } else {
                        $serviceDetails = implode('.', array_column($invoiceDetail->service->$supplierModel, 'serviceDetails'));
                        $identificationNumber = $invoiceDetail->service->identificationNumber;
                        $paxName = $customer->name;
                    }
                    ?>
                    <tr>
                        <td style="padding: .75rem;text-align: left;"><?= $invoiceDetail->service->formName() ?></td>
                        <td style="padding: .75rem;text-align: left;"><?= $identificationNumber ?></td>
                        <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->issueDate ?></td>
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
                    <tfoot style="background-color: #E3F0FF; color: #0a0a0a; text-align: left; font-weight: 700;font-size: 16px;">
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
            <h4>Contact:</h4>
            <?= $company->address ?><br>
            <p>
                <a href="mailto:ask@sharetrip.net"
                   style="color:#2A8CFF;font-weight:500;line-height:0.6;text-align:left;text-decoration:none;"><?= $company->email ?></a>
                |
                <a href="tel:+8809617617617"
                   style="color:#2A8CFF;font-weight:500;line-height:0.6;text-align:left;text-decoration:none;"><?= $company->phone ?></a>
            </p>
        </div>
    </div>
</div>
</body>
</html>