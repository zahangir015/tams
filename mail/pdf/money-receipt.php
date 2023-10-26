<?php

use app\components\Utilities;
use yii\helpers\ArrayHelper;

$logo = 'http://mytrams.com/uploads/company/'.$company['logo'];
$customer = $invoice['customer'];
dd($invoice);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Money Receipt <?= $transaction['transactionNumber'] ?></title>
    <style type="text/css">
        html {
            padding: 0;
            margin: 20px;
        }

        body {
            margin: 0;
            font-family: 'Helvetica','Arial',sans-serif;
            font-size: 12px;
            color: #333;
            /*padding: 20px;*/
        }

        h1, h2, h3, h4, h5, h6, p, ul, ol, li {
            margin: 0;
            padding: 0;
        }

        p {
            line-height: 1.5;
        }

        ul {
            margin: 10px 0;
        }

        li {
            margin: 7px 25px;
        }

        table {
            width: 100%;
            border: 0;
        }

        .wrap {
            border: 6px solid #2A8CFF;
            border-radius: 26px;
            padding: 16px;
            /*width: 800px;*/
        }

        .border-1x {
            border: 2px solid #b1b1b1;
            border-radius: 5px;
        }

        .border-in-1x {
            border: 1px solid #eee;
        }

        .bg-grey {
            background: #aaa;
        }

        .bg-light-grey {
            background: #ddd;
        }

        .padding-10 {
            padding: 10px;
        }

        .padding-sm {
            padding: 7px;
        }

        .padding-xs {
            padding: 2px;
        }

        .margin-b10 {
            margin-bottom: 10px;
        }

        .margin-b5 {
            margin-bottom: 10px;
        }

        .margin-b15 {
            margin-bottom: 15px;
        }

        .header h1 {
            color: #f47b20
        }

        .nav-alike {
            color: white;
            padding: 2px 3px;
        }

        .bold {
            font-weight: 700;
            color: #000000;
            font-size: 16px;
        }

        .contact-info p {
            padding-bottom: 5px;
        }

        .contact-info p a {
            color: #2062ae
        }

        .red-text {
            color: red;
            text-transform: uppercase;
        }
        .header{
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-bottom: 6px dashed #2A8CFF;
            padding-bottom: 16px;
            margin: 0 -16px;
            padding-right: 16px;
            padding-left: 16px;
        }
        .quick-info{
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header padding-10">
        <table border="0">
            <tr>
                <td width="50%">
                    <img src="<?= $logo; ?>" alt="<?= $logo ?>" style="max-width: 220px; height: auto;">
                </td>
                <td align="right" width="50%">
                    <h2><?= $company['name'] ?></h2>
                    <p style="text-align: right;">
                        <?= $company['address'] ?><br>
                        <?= $company['phone'].', '.$company['email'] ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <div class="content padding-10">
        <div class="quick-info margin-b10">
            <table border="0">
                <tr>
                    <td width="50%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="25%">Serial No:</td>
                                <td width="75%" class="bold"><?= $transaction['transactionNumber'] ?></td>
                            </tr>
                            <tr>
                                <td width="25%">Date</td>
                                <td width="75%" class="bold"><?= date('j F Y', strtotime($transaction['paymentDate'])) ?></td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" valign="top" style="text-align: right">
                        <p style="color: #245DD8; font-weight: bolder; font-style: italic; font-size: 36px;">Money Receipt <span><i>/</i><i>/</i><i>/</i></span> </p>
                    </td>
                </tr>
            </table>

            <table border="0">
                <tr>
                    <td width="20%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%">Received with thanks from</td>
                            </tr>
                        </table>
                    </td>
                    <td width="60%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%" class="bold" style="border-bottom: 2px solid #b1b1b1 !important; text-align: left"><?= $customer['name'] ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table border="0">
                <tr>
                    <td width="50%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="50%" valign="top">The sum of Taka</td>
                                <td width="50%" valign="top" class="border-1x bold padding-sm"><?= number_format($transaction['amount']) ?></td>
                            </tr>

                        </table>
                    </td>
                    <td width="50%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="50%" valign="top">Payment done in </td>
                                <td width="50%" valign="top" class="border-1x bold padding-sm"><?= $transaction['paymentMode'] ?></td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
            <table border="0">
                <tr>
                    <td width="20%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%">On account Of </td>
                            </tr>
                        </table>
                    </td>
                    <td width="60%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%" class="bold" style="border-bottom: 2px solid #b1b1b1 !important; text-align: left"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table border="0">
                <tr>
                    <td width="20%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%">Details </td>
                            </tr>
                        </table>
                    </td>
                    <td width="60%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%" class="bold" style="border-bottom: 2px solid #b1b1b1 !important; text-align: left"><?= ($invoice['chequeNumber']) ? 'Check Number -'.$invoice['chequeNumber'] : $invoice['remarks'] ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table border="0">
                <tr>
                    <td width="20%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%">Taka in words </td>
                            </tr>
                        </table>
                    </td>
                    <td width="60%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%" class="bold" style="border-bottom: 2px solid #b1b1b1 !important; text-align: left"><?= ucfirst(Utilities::convertNumber($transaction['amount'])); ?></td>
                            </tr>
                        </table>
                    </td>

                </tr>
            </table>
            <br>
            <br>
            <table border="0">
                <tr>
                    <td width="40%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%" class="bold" style="border-bottom: 2px solid #b1b1b1 !important; text-align: center"><?= Yii::$app->user->identity->employee->fullName ?></td>
                                <p style="text-align: center;box-sizing: inherit;">Received by</p>
                            </tr>
                        </table>
                    </td>
                    <td width="20%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%"></td>
                            </tr>
                        </table>
                    </td>
                    <td width="40%" valign="top">
                        <table border="0" cellspacing="5">
                            <tr>
                                <td width="100%" class="bold"  style="border-bottom: 2px solid #b1b1b1 !important; text-align: center"><?= $customer['name'] ?></td><br>
                                <p style="text-align: center">Paid by</p>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>