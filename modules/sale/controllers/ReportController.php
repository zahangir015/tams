<?php

namespace app\modules\sales\controllers;

use app\components\Constant;
use app\controllers\ParentController;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sales\models\hotel\Hotel;
use app\modules\sales\models\Insurance;
use app\modules\sales\models\package\Package;
use app\modules\sales\models\ticket\Ticket;
use app\modules\sales\models\ticket\TicketSupplier;
use app\modules\sales\models\visa\Visa;
use DateInterval;
use DatePeriod;
use DateTime;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class ReportController extends ParentController
{
    public function actionTicketSalesReport($dateRange = ''): string
    {
        if (!is_null($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);
            $date = date('jS \of F', strtotime($start_date)) . ' to ' . date('jS \of F', strtotime($end_date));
        } else {
            list($start_date, $end_date) = explode(' - ', date('Y-m-d') . ' - ' . date('Y-m-d'));
            $date = date('jS \of F');
        }

        $reportTypes = Yii::$app->request->get('reportType');


        if ($reportTypes && in_array('CUSTOMER_CATEGORY', $reportTypes)) {
            $customerCategoryWiseDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(receivedAmount) as receivedAmount'), 'customerCategory', 'type'
                ])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['customerCategory', 'type'])
                ->orderBy('total DESC')
                ->asArray()->all();

            $customerCategoryWiseRefundList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    'customerCategory'
                ])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['customerCategory'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($customerCategoryWiseDataList as $item) {
                $customerCategoryWiseData[$item['customerCategory']][] = $item;
                $key = array_search($item['customerCategory'], array_column($customerCategoryWiseRefundList, 'customerCategory'));
                if ($key !== false) {
                    $customerCategoryWiseRefundData[$item['customerCategory']] = $customerCategoryWiseRefundList[$key];
                } else {
                    $customerCategoryWiseRefundData[$item['customerCategory']] = [];
                }
            }
        }

        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(receivedAmount) as receivedAmount'), 'bookedOnline', 'type'])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['bookedOnline', 'type'])
                ->orderBy('total DESC')
                ->asArray()->all();

            $bookingTypeWiseRefundDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    'bookedOnline'])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['bookedOnline'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($bookingTypeWiseDataList as $item) {
                $bookingTypeWiseData[$item['bookedOnline']][] = $item;
                $key = array_search($item['bookedOnline'], array_column($bookingTypeWiseRefundDataList, 'bookedOnline'));
                if ($key !== false) {
                    $bookingTypeWiseRefundData[$item['bookedOnline']] = $bookingTypeWiseRefundDataList[$key];
                } else {
                    $bookingTypeWiseRefundData[$item['bookedOnline']] = [];
                }
            }
        }

        if ($reportTypes && in_array('FLIGHT_TYPE', $reportTypes)) {
            $flightTypeWiseDatalist = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit'), 'flightType', 'type'])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['type', 'flightType'])
                ->orderBy('total DESC')
                ->asArray()->all();

            $flightTypeWiseRefundDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit'), 'flightType'])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['flightType'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($flightTypeWiseDatalist as $item) {
                $flightTypeWiseData[$item['flightType']][] = $item;
                $key = array_search($item['flightType'], array_column($flightTypeWiseRefundDataList, 'flightType'));
                if ($key !== false) {
                    $flightTypeWiseRefundData[$item['flightType']] = $flightTypeWiseRefundDataList[$key];
                } else {
                    $flightTypeWiseRefundData[$item['flightType']] = [];
                }
            }

        }

        if ($reportTypes && in_array('GDS', $reportTypes)) {
            $gdsWiseDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit'), 'GDS', 'type'])
                ->with(['provider'])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['GDS', 'type'])
                ->orderBy('total DESC')
                ->asArray()->all();

            $gdsWiseRefundDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit'), 'GDS'])
                ->with(['provider'])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['GDS'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($gdsWiseDataList as $item) {
                $gdsWiseData[$item['GDS']][] = $item;
                $key = array_search($item['GDS'], array_column($gdsWiseRefundDataList, 'GDS'));
                if ($key !== false) {
                    $gdsWiseRefundData[$item['GDS']] = $gdsWiseRefundDataList[$key];
                } else {
                    $gdsWiseRefundData[$item['GDS']] = [];
                }
            }

            $gdsIds = array_keys($gdsWiseData);
            $gdsRefundIds = ArrayHelper::map($gdsWiseRefundDataList, 'GDS', 'GDS');
            $missingRefundData = array_diff($gdsRefundIds, $gdsIds);

            foreach ($missingRefundData as $item) {
                $key = array_search($item, array_column($gdsWiseRefundDataList, 'GDS'));
                $gdsWiseData[$item][] = $gdsWiseRefundDataList[$key];
            }
        }

        if ($reportTypes && in_array('AIRLINES', $reportTypes)) {
            $airlineDataList = Ticket::find()
                ->leftJoin('airlines', 'ticket.airlineId = airlines.id')
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(ticket.serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    'airlines.airlineName',
                    'airlineId',
                    'type'
                ])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['airlineId', 'type'])
                ->orderBy('total DESC')
                ->asArray()->all();

            $airlineWiseRefundDataList = Ticket::find()
                ->leftJoin('airlines', 'ticket.airlineId = airlines.id')
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(ticket.serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    'airlines.airlineName',
                    'airlineId'
                ])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['airlines.airlineName'])
                ->orderBy(['total' => SORT_DESC])
                ->asArray()->all();

            foreach ($airlineDataList as $item) {
                $airlineName = trim($item['airlineName']);
                $airlineWiseData[$airlineName][] = $item;
                $key = array_search($airlineName, array_column($airlineWiseRefundDataList, 'airlineName'));
                if ($key !== false) {
                    $airlineWiseRefundData[$airlineName] = $airlineWiseRefundDataList[$key];
                } else {
                    $airlineWiseRefundData[$airlineName] = [];
                }
            }
        }

        if ($reportTypes && in_array('ROUTING', $reportTypes)) {
            $routingWiseDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit'), 'routing', 'type'])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['type', 'routing'])
                ->orderBy(['total' => SORT_DESC])
                ->asArray()->all();

            $routingWiseRefundDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit'), 'routing'])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['routing'])
                ->orderBy(['total' => SORT_DESC])
                ->asArray()->all();

            foreach ($routingWiseDataList as $item) {
                $routingWiseData[$item['routing']][] = $item;
                $key = array_search($item['routing'], array_column($routingWiseRefundDataList, 'routing'));
                if ($key !== false) {
                    $routingWiseRefundData[$item['routing']] = $routingWiseRefundDataList[$key];
                } else {
                    $routingWiseRefundData[$item['routing']] = [];
                }
            }
        }

        if ($reportTypes && in_array('SUPPLIER', $reportTypes)) {
            $supplierWiseDataList = TicketSupplier::find()
                ->leftJoin('ticket', 'ticket.`id` = ticket_supplier.`ticketId`')
                ->leftJoin('suppliers', 'suppliers.`id` = ticket_supplier.`supplierId`')
                ->select([
                    new Expression('suppliers.name as name'),
                    new Expression('suppliers.supplierCompany as supplierCompany'),
                    new Expression('sum(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.serviceCharge) as serviceCharge'),
                    new Expression('SUM(ticket.discount) as discount'),
                    new Expression('SUM(ticket.markupAmount) as markupAmount'),
                    new Expression('SUM(ticket.convenienceFee) as convenienceFee'),
                    new Expression('SUM(ticket.commissionReceived) as commissionReceived'),
                    new Expression('SUM(ticket.incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(ticket_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'), 'ticket_supplier.supplierId'])
                ->where(['between', 'ticket_supplier.issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'ticket_supplier.type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['supplierId'])
                ->orderBy('numberOfSegment DESC')
                ->asArray()->all();

            $supplierWiseRefundDataList = TicketSupplier::find()
                ->leftJoin('ticket', 'ticket.`id` = ticket_supplier.`ticketId`')
                ->leftJoin('suppliers', 'suppliers.`id` = ticket_supplier.`supplierId`')
                ->select([
                    new Expression('suppliers.name as name'),
                    new Expression('suppliers.supplierCompany as supplierCompany'),
                    new Expression('sum(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.serviceCharge) as serviceCharge'),
                    new Expression('SUM(ticket.discount) as discount'),
                    new Expression('SUM(ticket.markupAmount) as markupAmount'),
                    new Expression('SUM(ticket.convenienceFee) as convenienceFee'),
                    new Expression('SUM(ticket.commissionReceived) as commissionReceived'),
                    new Expression('SUM(ticket.incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(ticket_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'), 'ticket_supplier.supplierId'])
                ->where(['between', 'ticket_supplier.issueDate', $start_date, $end_date])
                ->andWhere(['ticket_supplier.type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['supplierId'])
                ->orderBy(['numberOfSegment' => SORT_DESC])
                ->asArray()->all();

            foreach ($supplierWiseDataList as $item) {
                $supplierWiseData[$item['supplierCompany']][] = $item;
                $key = array_search($item['supplierCompany'], array_column($supplierWiseRefundDataList, 'userId'));
                if ($key !== false) {
                    $supplierWiseRefundData[$item['supplierCompany']] = $supplierWiseRefundDataList[$key];
                } else {
                    $supplierWiseRefundData[$item['supplierCompany']] = [];
                }
            }
        }

        if ($reportTypes && in_array('CUSTOMER', $reportTypes)) {
            $customerWiseDataList = Ticket::find()
                ->with(['customer'])
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit'), 'customerId', 'type'])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['customerId', 'type'])
                ->orderBy('total DESC')
                ->asArray()->all();
            $customerWiseRefundDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(baseFare) as baseFare'),
                    new Expression('SUM(tax) as tax'),
                    new Expression('SUM(otherTax) as otherTax'),
                    new Expression('SUM(serviceCharge) as serviceCharge'),
                    new Expression('SUM(discount) as discount'),
                    new Expression('SUM(markupAmount) as markupAmount'),
                    new Expression('SUM(convenienceFee) as convenienceFee'),
                    new Expression('SUM(ait) as ait'),
                    new Expression('SUM(commissionReceived) as commissionReceived'),
                    new Expression('SUM(incentiveReceived) as incentiveReceived'),
                    new Expression('SUM(netProfit) as netProfit'), 'customerId'])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['customerId'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
            foreach ($customerWiseDataList as $item) {
                $customerWiseData[$item['customerId']] = $item;
                $key = array_search($item['customerId'], array_column($customerWiseRefundDataList, 'customerId'));
                if ($key !== false) {
                    $customerWiseRefundData[$item['customerId']] = $customerWiseRefundDataList[$key];
                } else {
                    $customerWiseRefundData[$item['customerId']] = [];
                }
            }
        }

        return $this->render('ticket-sales-report', [
            'date' => $date,
            'supplierWiseData' => $supplierWiseData ?? [],
            'supplierWiseRefundData' => $supplierWiseRefundData ?? [],
            'routingWiseData' => $routingWiseData ?? [],
            'routingWiseRefundData' => $routingWiseRefundData ?? [],
            'flightTypeWiseData' => $flightTypeWiseData ?? [],
            'flightTypeWiseRefundData' => $flightTypeWiseRefundData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'customerWiseRefundData' => $customerWiseRefundData ?? [],
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'customerCategoryWiseRefundData' => $customerCategoryWiseRefundData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'bookingTypeWiseRefundData' => $bookingTypeWiseRefundData ?? [],
            'gdsWiseData' => $gdsWiseData ?? [],
            'gdsWiseRefundData' => $gdsWiseRefundData ?? [],
            'airlineWiseData' => $airlineWiseData ?? [],
            'airlineWiseRefundData' => $airlineWiseRefundData ?? [],
            'flightTypeData' => $flightTypeData ?? [],
            'flightTypeRefundData' => $flightTypeRefundData ?? [],
            'flightTypeWiseAirlineData' => $flightTypeWiseAirlineData ?? [],
            'flightTypeWiseAirlineRefundData' => $flightTypeWiseAirlineRefundData ?? [],
        ]);
    }

    public
    function actionHolidaySalesReport($dateRange = '', $type = ''): string
    {
        if (!is_null($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);
            $date = date('jS \of F', strtotime($start_date)) . ' to ' . date('jS \of F', strtotime($end_date));
        } else {
            list($start_date, $end_date) = explode(' - ', date('Y-m-d') . ' - ' . date('Y-m-d'));
            $date = date('jS \of F');
        }

        $reportTypes = Yii::$app->request->get('reportType');

        if ($reportTypes && in_array('CUSTOMER_CATEGORY', $reportTypes)) {
            // Customer category wise report with date range
            $customerCategoryWiseDataList = Holiday::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                    'customerCategory', 'type'
                ])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['customerCategory', 'type'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            $customerCategoryWiseRefundList = Holiday::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                    'customerCategory'
                ])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['customerCategory'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($customerCategoryWiseDataList as $item) {
                $customerCategoryWiseData[$item['customerCategory']][] = $item;
                $key = array_search($item['customerCategory'], array_column(($customerCategoryWiseRefundList) ?? [], 'customerCategory'));
                if ($key !== false) {
                    $customerCategoryWiseRefundData[$item['customerCategory']] = $customerCategoryWiseRefundList[$key];
                } else {
                    $customerCategoryWiseRefundData[$item['customerCategory']] = [];
                }
            }
        }

        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            // Customer category and booking type wise report with date range
            $bookingTypeWiseData = [];
            $bookingTypeWiseDataList = Holiday::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                    'customerCategory', 'bookedOnline', 'type'
                ])
                ->where(['<=', 'refundRequestDate', $end_date])
                ->orWhere(['IS', 'refundRequestDate', NULL])
                ->andWhere(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['customerCategory', 'bookedOnline', 'type'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            $bookingTypeWiseRefundList = Holiday::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                    'customerCategory', 'bookedOnline'
                ])
                ->where(['between', 'refundRequestDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy(['customerCategory', 'bookedOnline'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($bookingTypeWiseDataList as $item) {
                $bookedOnline = ($item['bookedOnline']) ? 'Online' : 'Offline';
                $bookingTypeWiseData[$item['customerCategory'] . ' ' . $bookedOnline][] = $item;
                $key = array_search($item['customerCategory'], array_column($bookingTypeWiseRefundList ?? [], 'customerCategory'));
                if ($key !== false) {
                    $bookingTypeWiseRefundData[$item['customerCategory'] . ' ' . $bookedOnline] = $bookingTypeWiseRefundList[$key];
                } else {
                    $bookingTypeWiseRefundData[$item['customerCategory'] . ' ' . $bookedOnline] = [];
                }
            }
        }


        // Route wise report with date range
        $routeWiseDataList = Holiday::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'route', 'type'
            ])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['route', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $routeWiseRefundDataList = Holiday::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'route'
            ])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['route'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($routeWiseDataList as $item) {
            $routeWiseData[$item['route']][] = $item;
            $key = array_search($item['route'], array_column($routeWiseRefundDataList ?? [], 'route'));
            if ($key !== false) {
                $routeWiseRefundData[$item['route']] = $routeWiseRefundDataList[$key];
            } else {
                $routeWiseRefundData[$item['route']] = [];
            }
        }

        // Customer wise report with date range
        $customerWiseDataList = Holiday::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerId', 'type'
            ])
            ->with(['customer'])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerId', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $customerWiseRefundDataList = Holiday::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerId'
            ])
            ->with(['customer'])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerId'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($customerWiseDataList as $item) {
            $customerWiseData[$item['customer']['company']][] = $item;
            $key = array_search($item['customer']['company'], array_column($customerWiseRefundDataList ?? [], 'route'));
            if ($key !== false) {
                $customerWiseRefundData[$item['customer']['company']] = $customerWiseRefundDataList[$key];
            } else {
                $customerWiseRefundData[$item['customer']['company']] = [];
            }
        }

        return $this->render('package-sales-report', [
            'date' => $date,
            'monthWisePackageData' => $monthWisePackageData ?? [],
            'monthWisePackageRefundData' => $monthWisePackageRefundData ?? [],
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'customerCategoryWiseRefundData' => $customerCategoryWiseRefundData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'bookingTypeWiseRefundData' => $bookingTypeWiseRefundData ?? [],
            'routeWiseData' => $routeWiseData ?? [],
            'routeWiseRefundData' => $routeWiseRefundData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'customerWiseRefundData' => $customerWiseRefundData ?? [],
        ]);
    }

    public
    function actionHotelSalesReport($dateRange = '', $type = ''): string
    {
        if (!is_null($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);
            $date = date('jS \of F', strtotime($start_date)) . ' to ' . date('jS \of F', strtotime($end_date));
        } else {
            list($start_date, $end_date) = explode(' - ', date('Y-m-d') . ' - ' . date('Y-m-d'));
            $date = date('jS \of F');
        }

        /* $reportTypes = Yii::$app->request->get('reportType');
         $date1 = DateTime::createFromFormat('Y-m-d', $start_date);
         $date2 = DateTime::createFromFormat('Y-m-d', $end_date);
         $diff = $date1->diff($date2)->m;
         $monthWiseHotelData = [];
         $monthWiseHotelRefundData = [];
         if ($diff >= 1) {
             $start = (new DateTime($start_date))->modify('first day of this month');
             $end = (new DateTime($end_date))->modify('first day of next month');
             $interval = DateInterval::createFromDateString('1 month');
             $period = new DatePeriod($start, $interval, $end);
             foreach ($period as $dt) {
                 $monthWiseHotelDataList = Hotel::find()
                     ->select([
                         new Expression('COUNT(id) as total'),
                         new Expression('SUM(costOfSale) as costOfSale'),
                         new Expression('SUM(quoteAmount) as quoteAmount'),
                         new Expression('SUM(receivedAmount) as receivedAmount'),
                         new Expression('SUM(netProfit) as netProfit'), 'packageCategoryId', 'type'])
                     ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                     ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                     ->groupBy(['packageCategoryId', 'type'])
                     ->orderBy('total DESC')
                     ->asArray()->all();
                 $monthWiseHotelRefundDataList = Hotel::find()
                     ->select([
                         new Expression('COUNT(id) as total'),
                         new Expression('SUM(costOfSale) as costOfSale'),
                         new Expression('SUM(quoteAmount) as quoteAmount'),
                         new Expression('SUM(receivedAmount) as receivedAmount'),
                         new Expression('SUM(netProfit) as netProfit'), 'packageCategoryId'])
                     ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                     ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                     ->groupBy('packageCategoryId')
                     ->orderBy('total DESC')
                     ->asArray()->one();
                 foreach ($monthWiseHotelDataList as $item) {
                     $monthWiseHotelData[$dt->format("Y-m")][$item['packageCategoryId']][] = $item;
                     $key = array_search($item['packageCategoryId'], array_column($monthWiseHotelRefundDataList, 'packageCategoryId'));
                     if ($key !== false) {
                         $monthWiseHotelRefundData[$dt->format("Y-m")][$item['packageCategoryId']] = $monthWiseHotelRefundDataList[$key];
                     } else {
                         $monthWiseHotelRefundData[$dt->format("Y-m")][$item['packageCategoryId']] = [];
                     }
                 }
             }
    
         } else {
             $monthWiseHotelDataList = Hotel::find()
                 ->select([
                     new Expression('COUNT(id) as total'),
                     new Expression('SUM(costOfSale) as costOfSale'),
                     new Expression('SUM(quoteAmount) as quoteAmount'),
                     new Expression('SUM(receivedAmount) as receivedAmount'),
                     new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                     new Expression('SUM(netProfit) as netProfit'), 'packageCategoryId', 'type'])
                 ->where(['between', 'issueDate', $start_date, $end_date])
                 ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                 ->groupBy(['packageCategoryId', 'type'])
                 ->orderBy('total DESC')
                 ->asArray()->all();
             $monthWiseHotelRefundDataList = Hotel::find()
                 ->select([
                     new Expression('COUNT(id) as total'),
                     new Expression('SUM(costOfSale) as costOfSale'),
                     new Expression('SUM(quoteAmount) as quoteAmount'),
                     new Expression('SUM(receivedAmount) as receivedAmount'),
                     new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                     new Expression('SUM(netProfit) as netProfit'), 'packageCategoryId'])
                 ->where(['between', 'issueDate', $start_date, $end_date])
                 ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                 ->groupBy('packageCategoryId')
                 ->orderBy('total DESC')
                 ->asArray()->one();
    
             foreach ($monthWiseHotelDataList as $item) {
                 $monthWiseHotelData[date("Y-m", strtotime($start_date))][$item['packageCategoryId']][] = $item;
                 $key = array_search($item['packageCategoryId'], array_column($monthWiseHotelRefundDataList, 'packageCategoryId'));
                 if ($key !== false) {
                     $monthWiseHotelRefundData[date("Y-m", strtotime($start_date))][$item['packageCategoryId']] = $monthWiseHotelRefundDataList[$key];
                 } else {
                     $monthWiseHotelRefundData[date("Y-m", strtotime($start_date))][$item['packageCategoryId']] = [];
                 }
             }
    
         }*/

        // Customer category wise report with date range
        $customerCategoryWiseDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalNights) as totalNights'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerCategory', 'type'
            ])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerCategory', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $customerCategoryWiseRefundList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalNights) as totalNights'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerCategory'
            ])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerCategory'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($customerCategoryWiseDataList as $item) {
            $customerCategoryWiseData[$item['customerCategory']][] = $item;
            $key = array_search($item['customerCategory'], array_column($customerCategoryWiseRefundList ?? [], 'customerCategory'));
            if ($key !== false) {
                $customerCategoryWiseRefundData[$item['customerCategory']] = $customerCategoryWiseRefundList[$key];
            } else {
                $customerCategoryWiseRefundData[$item['customerCategory']] = [];
            }
        }

        // Customer category and booking type wise report with date range
        $bookingTypeWiseData = [];
        $bookingTypeWiseDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalNights) as totalNights'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerCategory', 'bookedOnline', 'type'
            ])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerCategory', 'bookedOnline', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $bookingTypeWiseRefundList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalNights) as totalNights'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerCategory', 'bookedOnline'
            ])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerCategory', 'bookedOnline'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($bookingTypeWiseDataList as $item) {
            $bookedOnline = ($item['bookedOnline']) ? 'Online' : 'Offline';
            $bookingTypeWiseData[$item['customerCategory'] . ' ' . $bookedOnline][] = $item;
            $key = array_search($item['customerCategory'], array_column($bookingTypeWiseRefundList ?? [], 'customerCategory'));
            if ($key !== false) {
                $bookingTypeWiseRefundData[$item['customerCategory'] . ' ' . $bookedOnline] = $bookingTypeWiseRefundList[$key];
            } else {
                $bookingTypeWiseRefundData[$item['customerCategory'] . ' ' . $bookedOnline] = [];
            }
        }

        // Route wise report with date range
        $routeWiseDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalNights) as totalNights'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'route', 'type'
            ])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['route', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $routeWiseRefundDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalNights) as totalNights'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'route'
            ])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['route'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($routeWiseDataList as $item) {
            $routeWiseData[$item['route']][] = $item;
            $key = array_search($item['route'], array_column($routeWiseRefundDataList ?? [], 'route'));
            if ($key !== false) {
                $routeWiseRefundData[$item['route']] = $routeWiseRefundDataList[$key];
            } else {
                $routeWiseRefundData[$item['route']] = [];
            }
        }

        // Customer wise report with date range
        $customerWiseDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalNights) as totalNights'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerId', 'type'
            ])
            ->with(['customer'])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerId', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $customerWiseRefundDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(totalNights) as totalNights'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerId'
            ])
            ->with(['customer'])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerId'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($customerWiseDataList as $item) {
            $customerWiseData[$item['customer']['company']][] = $item;
            $key = array_search($item['customer']['company'], array_column($customerWiseRefundDataList ?? [], 'route'));
            if ($key !== false) {
                $customerWiseRefundData[$item['customer']['company']] = $customerWiseRefundDataList[$key];
            } else {
                $customerWiseRefundData[$item['customer']['company']] = [];
            }
        }

        return $this->render('hotel-sales-report', [
            'date' => $date,
//            'monthWiseHotelData' => $monthWiseHotelData ?? [],
//            'monthWiseHotelRefundData' => $monthWiseHotelRefundData ?? [],
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'customerCategoryWiseRefundData' => $customerCategoryWiseRefundData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'bookingTypeWiseRefundData' => $bookingTypeWiseRefundData ?? [],
            'routeWiseData' => $routeWiseData ?? [],
            'routeWiseRefundData' => $routeWiseRefundData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'customerWiseRefundData' => $customerWiseRefundData ?? [],
        ]);
    }

    public
    function actionVisaSalesReport($dateRange = '', $type = ''): string
    {
        if (!is_null($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);
            $date = date('jS \of F', strtotime($start_date)) . ' to ' . date('jS \of F', strtotime($end_date));
        } else {
            list($start_date, $end_date) = explode(' - ', date('Y-m-d') . ' - ' . date('Y-m-d'));
            $date = date('jS \of F');
        }

        /*$reportTypes = Yii::$app->request->get('reportType');
        $date1 = DateTime::createFromFormat('Y-m-d', $start_date);
        $date2 = DateTime::createFromFormat('Y-m-d', $end_date);
        $diff = $date1->diff($date2)->m;
        $monthWisePackageData = [];
        $monthWisePackageRefundData = [];
        if ($diff >= 1) {
            $start = (new DateTime($start_date))->modify('first day of this month');
            $end = (new DateTime($end_date))->modify('first day of next month');
            $interval = DateInterval::createFromDateString('1 month');
            $period = new DatePeriod($start, $interval, $end);
            foreach ($period as $dt) {
                $monthWisePackageDataList = Visa::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(costOfSale) as costOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(netProfit) as netProfit'), 'packageCategoryId', 'type'])
                    ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                    ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                    ->groupBy(['packageCategoryId', 'type'])
                    ->orderBy('total DESC')
                    ->asArray()->all();
                $monthWisePackageRefundDataList = Visa::find()
                    ->select([
                        new Expression('COUNT(id) as total'),
                        new Expression('SUM(costOfSale) as costOfSale'),
                        new Expression('SUM(quoteAmount) as quoteAmount'),
                        new Expression('SUM(receivedAmount) as receivedAmount'),
                        new Expression('SUM(netProfit) as netProfit'), 'packageCategoryId'])
                    ->where(['between', 'issueDate', $dt->format("Y-m-d"), $dt->format("Y-m-t")])
                    ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                    ->groupBy('packageCategoryId')
                    ->orderBy('total DESC')
                    ->asArray()->one();
                foreach ($monthWisePackageDataList as $item) {
                    $monthWisePackageData[$dt->format("Y-m")][$item['packageCategoryId']][] = $item;
                    $key = array_search($item['packageCategoryId'], array_column($monthWisePackageRefundDataList, 'packageCategoryId'));
                    if ($key !== false) {
                        $monthWisePackageRefundData[$dt->format("Y-m")][$item['packageCategoryId']] = $monthWisePackageRefundDataList[$key];
                    } else {
                        $monthWisePackageRefundData[$dt->format("Y-m")][$item['packageCategoryId']] = [];
                    }
                }
            }
    
        } else {
            $monthWisePackageDataList = Visa::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                    new Expression('SUM(netProfit) as netProfit'), 'packageCategoryId', 'type'])
                ->where(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
                ->groupBy(['packageCategoryId', 'type'])
                ->orderBy('total DESC')
                ->asArray()->all();
            $monthWisePackageRefundDataList = Visa::find()
                ->select([
                    new Expression('COUNT(id) as total'),
                    new Expression('SUM(costOfSale) as costOfSale'),
                    new Expression('SUM(quoteAmount) as quoteAmount'),
                    new Expression('SUM(receivedAmount) as receivedAmount'),
                    new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                    new Expression('SUM(netProfit) as netProfit'), 'packageCategoryId'])
                ->where(['between', 'issueDate', $start_date, $end_date])
                ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
                ->groupBy('packageCategoryId')
                ->orderBy('total DESC')
                ->asArray()->one();
    
            foreach ($monthWisePackageDataList as $item) {
                $monthWisePackageData[date("Y-m", strtotime($start_date))][$item['packageCategoryId']][] = $item;
                $key = array_search($item['packageCategoryId'], array_column($monthWisePackageRefundDataList, 'packageCategoryId'));
                if ($key !== false) {
                    $monthWisePackageRefundData[date("Y-m", strtotime($start_date))][$item['packageCategoryId']] = $monthWisePackageRefundDataList[$key];
                } else {
                    $monthWisePackageRefundData[date("Y-m", strtotime($start_date))][$item['packageCategoryId']] = [];
                }
            }
    
        }*/

        // Customer category wise report with date range
        $customerCategoryWiseDataList = Visa::find()
            ->select([
                new Expression('SUM(totalQty) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerCategory', 'type'
            ])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerCategory', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $customerCategoryWiseRefundList = Visa::find()
            ->select([
                new Expression('SUM(totalQty) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerCategory'
            ])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerCategory'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($customerCategoryWiseDataList as $item) {
            $customerCategoryWiseData[$item['customerCategory']][] = $item;
            $key = array_search($item['customerCategory'], array_column($customerCategoryWiseRefundList, 'customerCategory'));
            if ($key !== false) {
                $customerCategoryWiseRefundData[$item['customerCategory']] = $customerCategoryWiseRefundList[$key];
            } else {
                $customerCategoryWiseRefundData[$item['customerCategory']] = [];
            }
        }

        // Customer category and booking type wise report with date range
        $bookingTypeWiseData = [];
        $bookingTypeWiseDataList = Visa::find()
            ->select([
                new Expression('SUM(totalQty) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerCategory', 'bookedOnline', 'type'
            ])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerCategory', 'bookedOnline', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $bookingTypeWiseRefundList = Visa::find()
            ->select([
                new Expression('SUM(totalQty) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerCategory', 'bookedOnline'
            ])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerCategory', 'bookedOnline'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($bookingTypeWiseDataList as $item) {
            $bookedOnline = ($item['bookedOnline']) ? 'Online' : 'Offline';
            $bookingTypeWiseData[$item['customerCategory'] . ' ' . $bookedOnline][] = $item;
            $key = array_search($item['customerCategory'], array_column($bookingTypeWiseRefundList, 'customerCategory'));
            if ($key !== false) {
                $bookingTypeWiseRefundData[$item['customerCategory'] . ' ' . $bookedOnline] = $bookingTypeWiseRefundList[$key];
            } else {
                $bookingTypeWiseRefundData[$item['customerCategory'] . ' ' . $bookedOnline] = [];
            }
        }

        // Route wise report with date range
        $routeWiseDataList = Visa::find()
            ->select([
                new Expression('SUM(totalQty) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'countryId', 'type'
            ])
            ->with(['country'])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['countryId', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $routeWiseRefundDataList = visa::find()
            ->select([
                new Expression('SUM(totalQty) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'countryId'
            ])
            ->with(['country'])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['countryId'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($routeWiseDataList as $item) {
            $routeWiseData[$item['country']['name']][] = $item;
            $key = array_search($item['country']['name'], array_column($routeWiseRefundDataList ?? [], 'route'));
            if ($key !== false) {
                $routeWiseRefundData[$item['country']['name']] = $routeWiseRefundDataList[$key];
            } else {
                $routeWiseRefundData[$item['country']['name']] = [];
            }
        }

        // Customer wise report with date range
        $customerWiseDataList = Visa::find()
            ->select([
                new Expression('SUM(totalQty) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerId', 'type'
            ])
            ->with(['customer'])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerId', 'type'])
            ->orderBy('total DESC')
            ->asArray()
            ->all();

        $customerWiseRefundDataList = Visa::find()
            ->select([
                new Expression('SUM(totalQty) as total'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(quoteAmount-receivedAmount) as sum'),
                'customerId'
            ])
            ->with(['customer'])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->groupBy(['customerId'])
            ->orderBy('total DESC')
            ->asArray()->all();

        foreach ($customerWiseDataList as $item) {
            $customerWiseData[$item['customer']['company']][] = $item;
            $key = array_search($item['customer']['company'], array_column($customerWiseRefundDataList ?? [], 'route'));
            if ($key !== false) {
                $customerWiseRefundData[$item['customer']['company']] = $customerWiseRefundDataList[$key];
            } else {
                $customerWiseRefundData[$item['customer']['company']] = [];
            }
        }

        return $this->render('visa-sales-report', [
            'date' => $date,
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'customerCategoryWiseRefundData' => $customerCategoryWiseRefundData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'bookingTypeWiseRefundData' => $bookingTypeWiseRefundData ?? [],
            'routeWiseData' => $routeWiseData ?? [],
            'routeWiseRefundData' => $routeWiseRefundData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'customerWiseRefundData' => $customerWiseRefundData ?? [],
        ]);
    }

    public
    function actionSalesSummary(): string
    {
        list($start_date, $end_date) = explode(' - ', date('Y-m-d') . ' - ' . date('Y-m-d'));
        list($monthly_start_date, $monthly_end_date) = explode(' - ', date('Y-m-01') . ' - ' . date('Y-m-d'));
        list($previous_months_start_date, $previous_months_end_date) = explode(' - ', date("Y-m-01", strtotime("first day of previous month")) . ' - ' . date("Y-m-d", strtotime("last day of previous month")));

        // Daily Ticket Report Data
        $dailyTicketDataList = Ticket::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(numberOfSegment) as numberOfSegment'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(baseFare) as baseFare'),
                new Expression('SUM(tax) as tax'),
                new Expression('SUM(otherTax) as otherTax'),
                new Expression('SUM(serviceCharge) as serviceCharge'),
                new Expression('SUM(discount) as discount'),
                new Expression('SUM(markupAmount) as markupAmount'),
                new Expression('SUM(convenienceFee) as convenienceFee'),
                new Expression('SUM(ait) as ait'),
                new Expression('SUM(commissionReceived) as commissionReceived'),
                new Expression('SUM(incentiveReceived) as incentiveReceived'),
                'type'])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')->asArray()->all();

        $dailyTicketRefundDataList['Air Ticket'] = Ticket::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(numberOfSegment) as numberOfSegment'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(baseFare) as baseFare'),
                new Expression('SUM(tax) as tax'),
                new Expression('SUM(otherTax) as otherTax'),
                new Expression('SUM(serviceCharge) as serviceCharge'),
                new Expression('SUM(discount) as discount'),
                new Expression('SUM(markupAmount) as markupAmount'),
                new Expression('SUM(convenienceFee) as convenienceFee'),
                new Expression('SUM(ait) as ait'),
                new Expression('SUM(commissionReceived) as commissionReceived'),
                new Expression('SUM(incentiveReceived) as incentiveReceived'),
                'type'])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Daily Package Report
        $dailyPackageDataList = Package::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $dailyPackageRefundDataList = Package::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Daily Hotel Report
        $dailyHotelDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $dailyHotelRefundDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Daily Visa Report
        $dailyVisaDataList = Visa::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $start_date, $end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $dailyVisaRefundDataList = Visa::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $start_date, $end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Daily Insurance report
        $dailyInsuranceDataList = Insurance::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(price) as price'),
                new Expression('SUM(profit) as profit'), 'type'])
            ->where(['between', 'date', $start_date, $end_date])
            ->groupBy('type')
            ->orderBy('total DESC')
            ->asArray()->all();

        // Monthly Ticket Report Data
        $monthlyTicketDataList = Ticket::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(numberOfSegment) as numberOfSegment'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(baseFare) as baseFare'),
                new Expression('SUM(tax) as tax'),
                new Expression('SUM(otherTax) as otherTax'),
                new Expression('SUM(serviceCharge) as serviceCharge'),
                new Expression('SUM(discount) as discount'),
                new Expression('SUM(markupAmount) as markupAmount'),
                new Expression('SUM(convenienceFee) as convenienceFee'),
                new Expression('SUM(ait) as ait'),
                new Expression('SUM(commissionReceived) as commissionReceived'),
                new Expression('SUM(incentiveReceived) as incentiveReceived'),
                'type'])
            ->where(['<=', 'refundRequestDate', $monthly_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $monthlyTicketRefundDataList['Air Ticket'] = Ticket::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(numberOfSegment) as numberOfSegment'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(baseFare) as baseFare'),
                new Expression('SUM(tax) as tax'),
                new Expression('SUM(otherTax) as otherTax'),
                new Expression('SUM(serviceCharge) as serviceCharge'),
                new Expression('SUM(discount) as discount'),
                new Expression('SUM(markupAmount) as markupAmount'),
                new Expression('SUM(convenienceFee) as convenienceFee'),
                new Expression('SUM(ait) as ait'),
                new Expression('SUM(commissionReceived) as commissionReceived'),
                new Expression('SUM(incentiveReceived) as incentiveReceived'),
                'type'])
            ->where(['between', 'refundRequestDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Monthly Package Report Data
        $monthlyPackageDataList = Package::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $monthly_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $monthlyPackageRefundDataList = Package::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Monthly Hotel Report Data
        $monthlyHotelDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $monthly_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $monthlyHotelRefundDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Monthly Visa Report Data
        $monthlyVisaDataList = Visa::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $monthly_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $monthlyVisaRefundDataList = Visa::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Monthly Insurance report
        $monthlyInsuranceDataList = Insurance::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(price) as price'),
                new Expression('SUM(profit) as profit'), 'type'])
            ->where(['between', 'date', $monthly_start_date, $monthly_end_date])
            ->groupBy('type')
            ->orderBy('total DESC')
            ->asArray()->all();


        // Previous Months Ticket Report Data
        $previousMonthsTicketDataList = Ticket::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(numberOfSegment) as numberOfSegment'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(baseFare) as baseFare'),
                new Expression('SUM(tax) as tax'),
                new Expression('SUM(otherTax) as otherTax'),
                new Expression('SUM(serviceCharge) as serviceCharge'),
                new Expression('SUM(discount) as discount'),
                new Expression('SUM(markupAmount) as markupAmount'),
                new Expression('SUM(convenienceFee) as convenienceFee'),
                new Expression('SUM(ait) as ait'),
                new Expression('SUM(commissionReceived) as commissionReceived'),
                new Expression('SUM(incentiveReceived) as incentiveReceived'),
                'type'])
            ->where(['<=', 'refundRequestDate', $previous_months_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $previous_months_start_date, $previous_months_end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $previousMonthsTicketRefundDataList['Air Ticket'] = Ticket::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(numberOfSegment) as numberOfSegment'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(baseFare) as baseFare'),
                new Expression('SUM(tax) as tax'),
                new Expression('SUM(otherTax) as otherTax'),
                new Expression('SUM(serviceCharge) as serviceCharge'),
                new Expression('SUM(discount) as discount'),
                new Expression('SUM(markupAmount) as markupAmount'),
                new Expression('SUM(convenienceFee) as convenienceFee'),
                new Expression('SUM(ait) as ait'),
                new Expression('SUM(commissionReceived) as commissionReceived'),
                new Expression('SUM(incentiveReceived) as incentiveReceived'),
                'type'])
            ->where(['between', 'refundRequestDate', $previous_months_start_date, $previous_months_end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Previous Months Package Report Data
        $previousMonthsPackageDataList = Package::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $previous_months_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $previous_months_start_date, $previous_months_end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $previousMonthsPackageRefundDataList = Package::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $previous_months_start_date, $previous_months_end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Previous Months Hotel Report Data
        $previousMonthsHotelDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $previous_months_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $previous_months_start_date, $previous_months_end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $previousMonthsHotelRefundDataList = Hotel::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $previous_months_start_date, $previous_months_end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Previous Months Visa Report Data
        $previousMonthsVisaDataList = Visa::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $previous_months_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $previous_months_start_date, $previous_months_end_date])
            ->andWhere(['<>', 'type', Constant::TICKET_TYPE['Refund']])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $previousMonthsVisaRefundDataList = Visa::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $previous_months_start_date, $previous_months_end_date])
            ->andWhere(['type' => Constant::TICKET_TYPE['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Previous Months Insurance report
        $previousMonthsInsuranceDataList = Insurance::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(price) as price'),
                new Expression('SUM(profit) as profit'), 'type'])
            ->where(['between', 'date', $previous_months_start_date, $previous_months_end_date])
            ->groupBy('type')
            ->orderBy('total DESC')
            ->asArray()->all();

        /*dd([
            'dailyReportData' => [
                'Air Ticket' => [
                    'dailyTicketDataList' => $dailyTicketDataList ?? [],
                    'dailyTicketRefundDataList' => $dailyTicketRefundDataList ?? [],
                ],
                'Holidays' => [
                    'dailyPackageDataList' => $dailyPackageDataList ?? [],
                    'dailyPackageRefundDataList' => $dailyPackageRefundDataList ?? [],
                ],
                'Hotel' => [
                    'dailyHotelDataList' => $dailyHotelDataList ?? [],
                    'dailyHotelRefundDataList' => $dailyHotelRefundDataList ?? [],
                ],
                'Visa' => [
                    'dailyVisaDataList' => $dailyVisaDataList ?? [],
                    'dailyVisaRefundDataList' => $dailyVisaRefundDataList ?? [],
                ],
                'Insurance' => [
                    'dailyInsuranceDataList' => $dailyInsuranceDataList ?? [],
                ]
            ],
            'monthlyReportData' => [
                'Air Ticket' => [
                    'monthlyTicketDataList' => $monthlyTicketDataList ?? [],
                    'monthlyTicketRefundDataList' => $monthlyTicketRefundDataList ?? [],
                ],
                'Holidays' => [
                    'monthlyPackageDataList' => $monthlyPackageDataList ?? [],
                    'monthlyPackageRefundDataList' => $monthlyPackageRefundDataList ?? [],
                ],
                'Hotel' => [
                    'monthlyHotelDataList' => $monthlyHotelDataList ?? [],
                    'monthlyHotelRefundDataList' => $monthlyHotelRefundDataList ?? [],
                ],
                'Visa' => [
                    'monthlyVisaDataList' => $monthlyVisaDataList ?? [],
                    'monthlyVisaRefundDataList' => $monthlyVisaRefundDataList ?? [],
                ],
                'Insurance' => [
                    'monthlyInsuranceDataList' => $monthlyInsuranceDataList ?? [],
                ]
            ],
            'previousMonthsReportData' => [
                'Air Ticket' => [
                    'previousMonthsTicketDataList' => $previousMonthsTicketDataList ?? [],
                    'previousMonthsTicketRefundDataList' => $previousMonthsTicketRefundDataList ?? [],
                ],
                'Holidays' => [
                    'previousMonthsPackageDataList' => $previousMonthsPackageDataList ?? [],
                    'previousMonthsPackageRefundDataList' => $previousMonthsPackageRefundDataList ?? [],
                ],
                'Hotel' => [
                    'previousMonthsHotelDataList' => $previousMonthsHotelDataList ?? [],
                    'previousMonthsHotelRefundDataList' => $previousMonthsHotelRefundDataList ?? [],
                ],
                'Visa' => [
                    'previousMonthsVisaDataList' => $previousMonthsVisaDataList ?? [],
                    'previousMonthsVisaRefundDataList' => $previousMonthsVisaRefundDataList ?? [],
                ],
                'Insurance' => [
                    'previousMonthsInsuranceDataList' => $previousMonthsInsuranceDataList ?? [],
                ]
            ],
        ]);*/

        return $this->render('sales-summary-report', [
            'dailyReportData' => [
                'Air Ticket' => [
                    'dailyTicketDataList' => $dailyTicketDataList ?? [],
                    'dailyTicketRefundDataList' => $dailyTicketRefundDataList ?? [],
                ],
                'Holidays' => [
                    'dailyPackageDataList' => $dailyPackageDataList ?? [],
                    'dailyPackageRefundDataList' => $dailyPackageRefundDataList ?? [],
                ],
                'Hotel' => [
                    'dailyHotelDataList' => $dailyHotelDataList ?? [],
                    'dailyHotelRefundDataList' => $dailyHotelRefundDataList ?? [],
                ],
                'Visa' => [
                    'dailyVisaDataList' => $dailyVisaDataList ?? [],
                    'dailyVisaRefundDataList' => $dailyVisaRefundDataList ?? [],
                ],
                'Insurance' => [
                    'dailyInsuranceDataList' => $dailyInsuranceDataList ?? [],
                ]
            ],
            'monthlyReportData' => [
                'Air Ticket' => [
                    'monthlyTicketDataList' => $monthlyTicketDataList ?? [],
                    'monthlyTicketRefundDataList' => $monthlyTicketRefundDataList ?? [],
                ],
                'Holidays' => [
                    'monthlyPackageDataList' => $monthlyPackageDataList ?? [],
                    'monthlyPackageRefundDataList' => $monthlyPackageRefundDataList ?? [],
                ],
                'Hotel' => [
                    'monthlyHotelDataList' => $monthlyHotelDataList ?? [],
                    'monthlyHotelRefundDataList' => $monthlyHotelRefundDataList ?? [],
                ],
                'Visa' => [
                    'monthlyVisaDataList' => $monthlyVisaDataList ?? [],
                    'monthlyVisaRefundDataList' => $monthlyVisaRefundDataList ?? [],
                ],
                'Insurance' => [
                    'monthlyInsuranceDataList' => $monthlyInsuranceDataList ?? [],
                ]
            ],
            'previousMonthsReportData' => [
                'Air Ticket' => [
                    'previousMonthsTicketDataList' => $previousMonthsTicketDataList ?? [],
                    'previousMonthsTicketRefundDataList' => $previousMonthsTicketRefundDataList ?? [],
                ],
                'Holidays' => [
                    'previousMonthsPackageDataList' => $previousMonthsPackageDataList ?? [],
                    'previousMonthsPackageRefundDataList' => $previousMonthsPackageRefundDataList ?? [],
                ],
                'Hotel' => [
                    'previousMonthsHotelDataList' => $previousMonthsHotelDataList ?? [],
                    'previousMonthsHotelRefundDataList' => $previousMonthsHotelRefundDataList ?? [],
                ],
                'Visa' => [
                    'previousMonthsVisaDataList' => $previousMonthsVisaDataList ?? [],
                    'previousMonthsVisaRefundDataList' => $previousMonthsVisaRefundDataList ?? [],
                ],
                'Insurance' => [
                    'previousMonthsInsuranceDataList' => $previousMonthsInsuranceDataList ?? [],
                ]
            ],
        ]);
    }
}
