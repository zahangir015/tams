<?php

namespace app\modules\account\services;

use app\components\Helper;
use app\components\Utils;
use app\modules\account\models\RefundTransaction;
use app\modules\account\repositories\RefundTransactionRepository;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\visa\Visa;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class RefundTransactionService
{
    public RefundTransactionRepository $refundTransactionRepository;

    public function __construct()
    {
        $this->refundTransactionRepository = new RefundTransactionRepository();
    }

    public function getRefundList($refModel, $refId): array
    {
        $refundTransactions = RefundTransaction::find()
            ->select([
                'refund_transaction.id',
                'refund_transaction.refId',
                'refund_transaction.refModel',
                'concat(identificationNumber," | ",totalAmount) as name',
                'refund_transaction.identificationNumber',
                'refund_transaction.totalAmount',
                /*'refund_transaction.payableAmount',
                'refund_transaction.receivableAmount',
                'refund_transaction.adjustedAmount'*/
            ])
            ->where(['like', 'refund_transaction.refModel', $refModel])
            ->andWhere(['refund_transaction.refId' => $refId])
            ->all();

        return ArrayHelper::map($refundTransactions, 'id', 'name');
    }

    public function customerPending(array $requestData): array
    {
        $html = '';
        $totalReceivable = 0;
        $totalPayable = 0;
        $customerId = $requestData['customerId'];
        if (empty($customerId)) {
            if (empty($html)) {
                $html .= "<tr> <td colspan='6' class='text-danger text-center'> Refund not found </td> </tr>";
            }
            return ['html' => $html, 'totalPayable' => $totalPayable, 'totalReceivable' => $totalReceivable];
        }

        $start_date = $end_date = null;
        if (isset($requestData['dateRange']) && strpos($requestData['dateRange'], '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $requestData['dateRange']);
        }

        $refundData = $this->refundTransactionRepository->customerPendingRefundServices($customerId, $start_date, $end_date);

        if (!empty($refundData)) {
            if (!empty($refundData['tickets'])) {
                $response = self::rowGenerator($refundData['tickets'], Ticket::class);
                $html .= $response['html'];
                $totalReceivable += $response['totalReceivable'];
                $totalPayable += $response['totalPayable'];
            }
            if (!empty($refundData['hotels'])) {
                $response = self::rowGenerator($refundData['hotels'], Hotel::class);
                $html .= $response['html'];
                $totalReceivable += $response['totalReceivable'];
                $totalPayable += $response['totalPayable'];
            }
            if (!empty($refundData['visas'])) {
                $response = self::rowGenerator($refundData['visas'], Visa::class);
                $html .= $response['html'];
                $totalReceivable += $response['totalReceivable'];
                $totalPayable += $response['totalPayable'];
            }
            if (!empty($refundData['holidays'])) {
                $response = self::rowGenerator($refundData['visas'], Holiday::class);
                $html .= $response['html'];
                $totalReceivable += $response['totalReceivable'];
                $totalPayable += $response['totalPayable'];
            }
        }
        if (empty($html)) {
            $html .= "<tr> <td colspan='6' class='text-danger text-center'> Refund not found </td> </tr>";
        }
        return ['html' => $html, 'totalPayable' => $totalPayable, 'totalReceivable' => $totalReceivable];

    }

    public static function rowGenerator($services, $serviceModel): array
    {
        $html = '';
        $receivable = 0;
        $payable = 0;
        $totalReceivable = 0;
        $totalPayable = 0;
        foreach ($services as $key => $service) {
            $amount = floatval($service['quoteAmount']) - floatval($service['receivedAmount']);
            if ($amount == 0) {
                continue;
            }
            if ($amount < 1) {
                $payable = abs($amount);
                $totalPayable += abs($amount);
            } else {
                $receivable = $amount;
                $totalReceivable += $amount;
            }

            $array = [
                'refId' => $service['id'],
                'refModel' => $serviceModel,
                'serviceName' => ucfirst(Helper::getServiceName($serviceModel)),
                'payable' => $payable,
                'receivable' => $receivable,
                'amount' => abs($amount),
                'quoteAmount' => $service['quoteAmount'],
            ];

            $html .= '<tr>';
            $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="RefundTransactionDetail[' . $key . ']" value="' . htmlspecialchars(json_encode($array)) . '"></td>';
            $html .= '<td><span class="badge bg-green">' . ($serviceModel == Ticket::class) ? $service['eTicket'] : $service['identificationNumber'] . '</span></td>';
            $html .= '<td>' . $array['serviceName'] . ' <input type="text"  value="' . $array['refModel'] . '" hidden >  </td>';
            $html .= '<td>' . $service['issueDate'] . '</td>';
            $html .= '<td>' . $payable . '<input type="text" class="amount payable" id="payable' . $key . '"    value="' . $payable . '" hidden></td>';
            $html .= '<td>' . $receivable . '<input type="text" class="amount receivable" id="receivable' . $key . '"  value="' . $receivable . '" hidden></td>';
            $html .= '</tr>';
        }

        return ['html' => $html, 'totalPayable' => $totalPayable, 'totalReceivable' => $totalReceivable];
    }
}