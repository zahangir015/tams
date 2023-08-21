<?php
$logo = Yii::$app->basePath . '/web/uploads/' . \app\models\Company::findOne(1)->logo;
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
            <h3 style="color: #000000; text-align: left; font-size: 20px; font-weight: 700">
                INVOICE:&nbsp;<?= $invoice->invoiceNumber ?>
            </h3>
            <div style="width: 50%; float: left; position: relative; text-align: left; font-size: 16px;color: #000000;">
                <p>
                    <span>Customer Details</span><br>
                    <strong><?= $customer->name ?></strong><br>
                    <strong><?= $customer->address ?></strong>
                </p>
            </div>
            <div style="width: 45%; float: left; position: relative; text-align: left; font-size: 16px;color: #000000;">
                <p>
                    Date:<br>
                    <strong><?= date('j F Y', strtotime($invoice->createdAt)) ?></strong>
                </p>
            </div>
            <div style=" clear: both;"></div>
            <div style="width: 50%; float: left; position: relative; text-align: left;color: #000000;">
                <p>
                    Payment Due Date:<br>
                    <strong><?= ($invoice->expectedDate) ? date('j F Y', strtotime($invoice->expectedDate)) : '' ?> </strong>
                </p>
            </div>
            <div style="width: 45%; float: left; position: relative; text-align: left;color: #000000;">
                <p>
                    Reference Number:<br>
                    <strong><?= ($invoice->couponInvoiceNumber) ? $invoice->couponInvoiceNumber : '' ?> </strong>
                </p>
            </div>
            <div style=" clear: both;"></div>
            <div style="background: #ffffff; margin-top: 10px; border-radius: 4px;">
                <?php
                foreach ($invoice->details as $key => $invoiceDetail) {
                ?>
                <h2 style="color: #000000; font-weight: 600; width: 100%; font-size: 16px">
                    <?= $invoiceDetail->service->formName() ?>
                </h2>
                <table width="100%"
                       style="margin: auto; font-size: 14px; border: none !important; border-collapse: collapse">
                    <thead style="color: #0a0a0a; font-weight: 700; text-align: left; background-color: #e0e0e1; padding: .75rem;">
                    <?php
                    if ($invoiceDetail->service->formName() == 'Ticket') {
                        ?>
                        <tr>
                            <th style="padding: .75rem;">eTicket</th>
                            <th style="padding: .75rem;">Issue</th>
                            <th style="padding: .75rem;">Route</th>
                            <th style="padding: .75rem; text-align: right">paid Amount</th>
                            <th style="padding: .75rem; text-align: right">Due Amount</th>
                        </tr>
                        <?php
                    } elseif ($invoiceDetail->service->formName() == 'Visa') {
                        ?>
                        <tr>
                            <th style="padding: .75rem;">Identification #</th>
                            <th style="padding: .75rem;">Issue</th>
                            <th style="padding: .75rem;">Details</th>
                            <th style="padding: .75rem;">Paid Amount</th>
                            <th style="padding: .75rem; text-align: right">Due Amount</th>
                        </tr>
                        <?php
                    } elseif ($invoiceDetail->service->formName() == 'Hotel') {
                        ?>
                        <tr>
                            <th style="padding: .75rem;">Identification #</th>
                            <th style="padding: .75rem;">Issue</th>
                            <th style="padding: .75rem;">Details</th>
                            <th style="padding: .75rem;">Paid Amount</th>
                            <th style="padding: .75rem; text-align: right">Due Amount</th>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <th style="padding: .75rem;">Identification #</th>
                            <th style="padding: .75rem;">Issue</th>
                            <th style="padding: .75rem;">Details</th>
                            <th style="padding: .75rem;">Paid Amount</th>
                            <th style="padding: .75rem; text-align: right">Due Amount</th>
                        </tr>
                        <?php
                    }
                    ?>
                    </thead>
                    <tbody>
                    <?php
                    $totalAmount = 0;
                    if ($invoiceDetail->service->formName() == 'Ticket') {
                        ?>
                        <tr>
                            <td style="padding: .75rem;text-align: left;"><?= $invoiceDetail->service->eTicket ?></td>
                            <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->issuDate ?></td>
                            <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->routing ?></td>
                            <td style="padding: .75rem; text-align: right;"><?= number_format($invoiceDetail->paidAmount) ?></td>
                            <td style="padding: .75rem; text-align: right;"><?= number_format($invoiceDetail->dueAmount) ?></td>
                        </tr>
                        <?php
                    } elseif ($invoiceDetail->service->formName() == 'Visa') {
                        $serviceDetails = implode('.', array_search())
                        ?>
                        <tr>
                            <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->identificationNumber ?></td>
                            <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->issueDate ?></td>
                            <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->visasupplier->serviceDetails ?></td>
                            <td style="padding: .75rem; text-align: left;"><?= number_format($invoiceDetail->paidAmount) ?></td>
                            <td style="padding: .75rem; text-align: right;"><?= number_format($invoiceDetail->dueAmount) ?></td>
                        </tr>
                        <?php
                    } elseif ($invoiceDetail->service->formName() == 'Hotel') { ?>
                        <tr>
                            <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->identificationNumber ?></td>
                            <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->issueDate ?></td>
                            <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->details ?></td>
                            <td style="padding: .75rem; text-align: left;"><?= number_format($invoiceDetail->paidAmount) ?></td>
                            <td style="padding: .75rem; text-align: right;"><?= number_format($invoiceDetail->dueAmount) ?></td>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->identificationNumber ?></td>
                        <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->issueDate ?></td>
                        <td style="padding: .75rem; text-align: left;"><?= $invoiceDetail->service->details ?></td>
                        <td style="padding: .75rem; text-align: left;"><?= number_format($invoiceDetail->paidAmount) ?></td>
                        <td style="padding: .75rem; text-align: right;"><?= number_format($invoiceDetail->dueAmount) ?></td>
                    <?php
                    }
                    ?>
                    </tbody>
                    <tfoot style="background-color: #e0e0e1; color: #0a0a0a; text-align: left; font-weight: 700;font-size: 16px;">
                    <?php if (!$invoice->breakDown) { ?>
                    <tr>
                        <td colspan="4"
                            style="padding: .75rem; text-align: left; font-weight: 700; font-size: 16px">
                            Total Amount
                        </td>
                        <td style="padding: .75rem; text-align: right; font-weight: 700; font-size: 16px">
                            BDT <?= number_format($totalAmount) ?></td>
                    </tr>
                    <?php
                    if ($invoice->serviceCharge || $invoice->vat || $invoice->paymentCharge || $invoice->AIT) {
                    ?>
                    <tr>
                        <td colspan="4"
                            style="padding: .75rem; text-align: left; font-weight: 700; font-size: 16px">
                            Service Charge
                        </td>
                        <td style="padding: .75rem; text-align: right; font-weight: 700; font-size: 16px">
                            BDT <?= number_format($invoice->serviceCharge) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding: .75rem; text-align: left; font-weight: 700; font-size: 16px">
                            VAT(Value Added Service)
                        </td>
                        <td style="padding: .75rem; text-align: right; font-weight: 700; font-size: 16px">
                            BDT <?= number_format($invoice->vat) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding: .75rem; text-align: left; font-weight: 700; font-size: 16px">
                            AIT(Advance Income Tax)
                        </td>
                        <td style="padding: .75rem; text-align: right; font-weight: 700; font-size: 16px">
                            BDT <?= number_format($invoice->AIT) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="padding: .75rem; text-align: left; font-weight: 700; font-size: 16px">
                            Grand Total
                        </td>
                        <td style="padding: .75rem; text-align: right; font-weight: 700; font-size: 16px"> BDT <?= number_format(($totalAmount + $invoice->vat + $invoice->AIT)) ?></td>
                    </tr>
                    <?php
                    }
                    ?>

                        <?php } else { ?>
                    <tr>
                        <td colspan="4" style="padding: .75rem;">
                            Total Amount
                        </td>
                        <td style="padding: .75rem; text-align: right">
                            BDT <?= number_format($totalAmount) ?></td>
                    </tr>

                    <?php
                    if ($invoice->serviceCharge || $invoice->vat || $invoice->paymentCharge || $invoice->AIT) {
                    ?>
                    <tr>
                        <td colspan="4"
                            style="padding: .75rem; text-align: left; font-weight: 700; font-size: 16px">
                            Service Charge
                        </td>
                        <td style="padding: .75rem; text-align: right; font-weight: 700; font-size: 16px">
                            BDT <?= number_format($invoice->serviceCharge) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding: .75rem;">
                            VAT(Value Added Service)
                        </td>
                        <td style="padding: .75rem; text-align: right">
                            BDT <?= number_format($invoice->vat) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding: .75rem;">
                            AIT(Advance Income Tax)
                        </td>
                        <td style="padding: .75rem; text-align: right">
                            BDT <?= number_format($invoice->AIT) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding: .75rem;">
                            Grand Total
                        </td>
                        <td style="padding: .75rem; text-align: right">
                            BDT <?= number_format(($totalAmount + $invoice->vat + $invoice->AIT)) ?></td>
                    </tr>
                    <?php
                    }
                    }
                    ?>

                    </tfoot>
                </table>
                <?php
                }
                ?>
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
                            BDT <?= number_format($invoice->due) ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="80%" style="padding: .75rem;">
                            Total Paid
                        </td>
                        <td width="20%" style="padding: .75rem; text-align: right">
                            BDT <?= number_format($invoice->amount) ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div style="clear: both"></div>

        <div style="background: #EFEFF4; box-sizing: border-box; padding: 15px; padding-bottom: 32px; padding-top: 32px; text-align: left; width: 100%; color: #474749;line-height: 0.5; font-size: 16px; margin-top: 10px; text-align: center">
            <h4>Contact:</h4>
            <?= CompanyProfile::findOne(['id' => 1])->address ?><br>
            <p>
                <a href="mailto:ask@sharetrip.net"
                   style="color:#2A8CFF;font-weight:700;line-height:1.6;text-align:left;text-decoration:none;">ask@sharetrip.net</a>
                |
                <a href="tel:+8809617617617"
                   style="color:#2A8CFF;font-weight:700;line-height:1.6;text-align:left;text-decoration:none;">+8809617617617</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>