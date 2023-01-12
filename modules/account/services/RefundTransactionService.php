<?php

namespace app\modules\account\services;

use app\components\Utils;
use app\modules\account\models\RefundTransaction;
use app\modules\account\repositories\RefundTransactionRepository;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\visa\Visa;
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

    public function customerPending(array $requestData)
    {
        $customerId = $requestData['customerId'];
        if (empty($customerId)) {
            return false;
        }
        $start_date = $end_date = null;
        if (isset($requestData['dateRange']) && strpos($requestData['dateRange'], '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $requestData['dateRange']);
        }
        $refundData = $this->refundTransactionRepository->customerPendingRefundServices($customerId, $start_date, $end_date);
        dd($refundData);
    }

    public static function refundServiceRowGenerationForCustomer($refundData): string
    {
        $html = '';
        if (!empty($refundData['refundTickets'])) {
            $html .= self::getHtmlRowsForServiceRefunds($refundData->refundTickets, $serviceModel = Ticket::class);
        }
        if (!empty($refundData->refundHotels)) {
            $html .= self::getHtmlRowsForServiceRefunds($refundData->refundHotels, $serviceModel = Hotel::class);
        }
        if (!empty($refundData->refundVisas)) {
            $html .= self::getHtmlRowsForServiceRefunds($refundData->refundVisas, $serviceModel = Visa::class);
        }

        if (!empty($refundData->refundHolidays)) {
            $html .= self::getHtmlRowsForServiceRefunds($refundData->refundHolidays, $serviceModel = Holiday::class);
        }

        return empty($html) ? "<tr> <td colspan='6' class='text-danger text-center'> Not found any sales to refund </td> </tr>" : $html;
    }

    public static function getHtmlRowsForServiceRefunds($services, $serviceModel): string
    {
        $html = '';
        $receivable = 0;
        $payable = 0;
        foreach ($services as $key => $service) {
            $amount = floatval($service['quoteAmount']) - floatval($service['receivedAmount']);
            if ($amount == 0) {
                continue;
            }
            if ($amount < 1) {
                $payable = abs($amount);
            } else {
                $receivable = $amount;
            }
            $array = [
                'refId' => $service['serviceId'],
                'refModel' => $serviceModel,
                'serviceName' => ucfirst(Utils::getServiceName($serviceModel)),
                'payable' => $payable,
                'receivable' => $receivable,
                'amount' => abs($amount),
                'quoteAmount' => $service['quoteAmount'],
            ];

            $html .= '<tr>';
            $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="RefundTransactionDetail[' . $key . ']" value="' . htmlspecialchars(json_encode($array)) . '"></td>';
            $html .= '<td><span class="badge bg-green">' . $service['identificationNo'] . '</span></td>';
            $html .= '<td>' . $array['serviceName'] . ' <input type="text"  value="' . $array['refModel'] . '" hidden >  </td>';
            $html .= '<td>' . $service['issueDate'] . '</td>';
            $html .= '<td>' . $payable . '<input type="text" class="amount payable" id="payable' . $key . '"    value="' . $payable . '" hidden></td>';
            $html .= '<td>' . $receivable . '<input type="text" class="amount receivable" id="receivable' . $key . '"  value="' . $receivable . '" hidden></td>';
            $html .= '</tr>';
        }

        return $html;
    }
}