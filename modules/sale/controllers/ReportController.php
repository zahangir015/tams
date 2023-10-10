<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\controllers\ParentController;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidaySupplier;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\hotel\HotelSupplier;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\models\visa\Visa;
use app\modules\sale\models\visa\VisaSupplier;
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
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    Ticket::tableName().'.customerCategory',
                ])
                ->where(['<=', Ticket::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Ticket::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Ticket::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Ticket::tableName().'.customerCategory'])
                ->orderBy('total DESC')
                ->asArray()->all();
            foreach ($customerCategoryWiseDataList as $categoryData) {
                $gross = ($categoryData['baseFare'] + $categoryData['tax'] + $categoryData['otherTax']);
                $due = ($categoryData['quoteAmount'] - $categoryData['receivedAmount']);
                $customerCategoryWiseData[] = [
                    'category' => $categoryData['customerCategory'],
                    'qty' => $categoryData['total'],
                    'totalSegments' => $categoryData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $categoryData['quoteAmount'],
                    'totalReceived' => $categoryData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $categoryData['netProfit'],
                ];
            }
        }

        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    Ticket::tableName().'.bookedOnline'
                ])
                ->where(['<=', Ticket::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Ticket::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Ticket::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Ticket::tableName().'.bookedOnline'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($bookingTypeWiseDataList as $bookingTypeData) {
                $gross = ($bookingTypeData['baseFare'] + $bookingTypeData['tax'] + $bookingTypeData['otherTax']);
                $due = ($bookingTypeData['quoteAmount'] - $bookingTypeData['receivedAmount']);
                $bookingTypeWiseData[] = [
                    'category' => ServiceConstant::BOOKING_TYPE[$bookingTypeData['bookedOnline']],
                    'qty' => $bookingTypeData['total'],
                    'totalSegments' => $bookingTypeData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $bookingTypeData['quoteAmount'],
                    'totalReceived' => $bookingTypeData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $bookingTypeData['netProfit'],
                ];
            }

        }

        if ($reportTypes && in_array('FLIGHT_TYPE', $reportTypes)) {
            $flightTypeWiseDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    Ticket::tableName().'.flightType'
                ])
                ->where(['<=', Ticket::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Ticket::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Ticket::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Ticket::tableName().'.flightType'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($flightTypeWiseDataList as $flightTypeData) {
                $gross = ($flightTypeData['baseFare'] + $flightTypeData['tax'] + $flightTypeData['otherTax']);
                $due = ($flightTypeData['quoteAmount'] - $flightTypeData['receivedAmount']);
                $flightTypeWiseData[] = [
                    'flightType' => ServiceConstant::FLIGHT_TYPE[$flightTypeData['flightType']],
                    'qty' => $flightTypeData['total'],
                    'totalSegments' => $flightTypeData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $flightTypeData['quoteAmount'],
                    'totalReceived' => $flightTypeData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $flightTypeData['netProfit'],
                ];
            }
        }

        if ($reportTypes && in_array('PROVIDER', $reportTypes)) {
            $providerWiseDataList = Ticket::find()
                ->joinWith(['provider'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    Ticket::tableName().'.providerId'
                ])
                ->where(['<=', Ticket::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Ticket::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Ticket::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Ticket::tableName().'.providerId'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($providerWiseDataList as $providerData) {
                $gross = ($providerData['baseFare'] + $providerData['tax'] + $providerData['otherTax']);
                $due = ($providerData['quoteAmount'] - $providerData['receivedAmount']);
                $providerWiseData[] = [
                    'provider' => ($providerData['provider']) ? $providerData['provider']['name'] : 'Not Set',
                    'qty' => $providerData['total'],
                    'totalSegments' => $providerData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $providerData['quoteAmount'],
                    'totalReceived' => $providerData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $providerData['netProfit'],
                ];
            }
        }

        if ($reportTypes && in_array('AIRLINES', $reportTypes)) {
            $airlineWiseDataList = Ticket::find()
                ->joinWith(['airline'])
                ->select([
                    new Expression('airline.name as name'),
                    new Expression('airline.code as code'),
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    Ticket::tableName().'.airlineId'
                ])
                ->where(['<=', Ticket::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Ticket::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Ticket::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Ticket::tableName().'.airlineId'])
                ->orderBy('total DESC')
                ->asArray()->all();

            foreach ($airlineWiseDataList as $airlineData) {
                $gross = ($airlineData['baseFare'] + $airlineData['tax'] + $airlineData['otherTax']);
                $due = ($airlineData['quoteAmount'] - $airlineData['receivedAmount']);
                $airlineWiseData[] = [
                    'name' => $airlineData['name'] . '(' . $airlineData['code'] . ')',
                    'qty' => $airlineData['total'],
                    'totalSegments' => $airlineData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $airlineData['quoteAmount'],
                    'totalReceived' => $airlineData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $airlineData['netProfit'],
                ];
            }
        }

        if ($reportTypes && in_array('ROUTING', $reportTypes)) {
            $routingWiseDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    Ticket::tableName().'.route'
                ])
                ->where(['<=', Ticket::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Ticket::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Ticket::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Ticket::tableName().'.route'])
                ->orderBy(['total' => SORT_DESC])
                ->asArray()->all();
            foreach ($routingWiseDataList as $routingData) {
                $gross = ($routingData['baseFare'] + $routingData['tax'] + $routingData['otherTax']);
                $due = ($routingData['quoteAmount'] - $routingData['receivedAmount']);
                $routingWiseData[] = [
                    'route' => $routingData['route'],
                    'qty' => $routingData['total'],
                    'totalSegments' => $routingData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $routingData['quoteAmount'],
                    'totalReceived' => $routingData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $routingData['netProfit'],
                ];
            }
        }

        if ($reportTypes && in_array('SUPPLIER', $reportTypes)) {
            $supplierWiseDataList = TicketSupplier::find()
                ->leftJoin('ticket', Ticket::tableName().'.id = ticket_supplier.ticketId')
                ->leftJoin('supplier', 'supplier.`id` = ticket_supplier.`supplierId`')
                ->select([
                    new Expression('supplier.name as name'),
                    new Expression('supplier.company as company'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    TicketSupplier::tableName().'.supplierId'
                ])
                ->where(['between', TicketSupplier::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([TicketSupplier::tableName().'.supplierId'])
                ->orderBy('numberOfSegment DESC')
                ->asArray()->all();

            foreach ($supplierWiseDataList as $supplierData) {
                $gross = ($supplierData['baseFare'] + $supplierData['tax'] + $supplierData['otherTax']);
                $due = ($supplierData['quoteAmount'] - $supplierData['receivedAmount']);
                $supplierWiseData[] = [
                    'name' => $supplierData['name'] . '(' . $supplierData['company'] . ')',
                    'qty' => $supplierData['total'],
                    'totalSegments' => $supplierData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $supplierData['quoteAmount'],
                    'totalReceived' => $supplierData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $supplierData['netProfit'],
                ];
            }
        }

        if ($reportTypes && in_array('CUSTOMER', $reportTypes)) {
            $customerWiseDataList = Ticket::find()
                ->joinWith(['customer'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    Ticket::tableName().'.customerId'
                ])
                ->where(['<=', Ticket::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Ticket::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Ticket::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Ticket::tableName().'.customerId'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerWiseDataList as $customerData) {
                $gross = ($customerData['baseFare'] + $customerData['tax'] + $customerData['otherTax']);
                $due = ($customerData['quoteAmount'] - $customerData['receivedAmount']);
                $customerWiseData[] = [
                    'name' => $customerData['customer']['name'],
                    'qty' => $customerData['total'],
                    'totalSegments' => $customerData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $customerData['quoteAmount'],
                    'totalReceived' => $customerData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $customerData['netProfit'],
                ];
            }
        }

        // Customer category and booking type wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY_BOOKING_TYPE', $reportTypes)) {
            $customerCategoryBookingTypeWiseDataList = Ticket::find()
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    Ticket::tableName().'.customerCategory', Ticket::tableName().'.bookedOnline'
                ])
                ->where(['<=', Ticket::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Ticket::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Ticket::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Ticket::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Ticket::tableName().'.customerCategory', Ticket::tableName().'.bookedOnline'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerCategoryBookingTypeWiseDataList as $categoryBookingTypeData) {
                $gross = ($categoryBookingTypeData['baseFare'] + $categoryBookingTypeData['tax'] + $categoryBookingTypeData['otherTax']);
                $due = ($categoryBookingTypeData['quoteAmount'] - $categoryBookingTypeData['receivedAmount']);
                $customerCategoryBookingTypeWiseData[] = [
                    'name' => $categoryBookingTypeData['customerCategory'] . ' ' . ServiceConstant::BOOKING_TYPE[$categoryBookingTypeData['bookedOnline']],
                    'qty' => $categoryBookingTypeData['total'],
                    'totalSegments' => $categoryBookingTypeData['numberOfSegment'],
                    'gross' => $gross,
                    'totalQuote' => $categoryBookingTypeData['quoteAmount'],
                    'totalReceived' => $categoryBookingTypeData['receivedAmount'],
                    'totalDue' => $due,
                    'netProfit' => $categoryBookingTypeData['netProfit'],
                ];
            }
        }

        return $this->render('ticket-sales-report', [
            'date' => $date,
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'customerCategoryBookingTypeWiseData' => $customerCategoryBookingTypeWiseData ?? [],
            'flightTypeWiseData' => $flightTypeWiseData ?? [],
            'routingWiseData' => $routingWiseData ?? [],
            'providerWiseData' => $providerWiseData ?? [],
            'airlineWiseData' => $airlineWiseData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'supplierWiseData' => $supplierWiseData ?? [],
        ]);
    }

    public
    function actionHolidaySalesReport($dateRange = ''): string
    {
        if (!is_null($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);
            $date = date('jS \of F', strtotime($start_date)) . ' to ' . date('jS \of F', strtotime($end_date));
        } else {
            list($start_date, $end_date) = explode(' - ', date('Y-m-d') . ' - ' . date('Y-m-d'));
            $date = date('jS \of F');
        }

        $reportTypes = Yii::$app->request->get('reportType');

        // Customer category wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY', $reportTypes)) {
            $customerCategoryWiseDataList = Holiday::find()
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                    'customerCategory'
                ])
                ->where(['<=', Holiday::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Holiday::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Holiday::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Holiday::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Holiday::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Holiday::tableName().'.customerCategory'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerCategoryWiseDataList as $categoryData) {
                $customerCategoryWiseData[] = [
                    'category' => $categoryData['customerCategory'],
                    'qty' => $categoryData['total'],
                    'totalQuote' => $categoryData['quoteAmount'],
                    'totalReceived' => $categoryData['receivedAmount'],
                    'totalDue' => ($categoryData['quoteAmount'] - $categoryData['receivedAmount']),
                    'netProfit' => $categoryData['netProfit'],
                ];
            }
        }
        // Booking type wise report
        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseDataList = Holiday::find()
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                    Holiday::tableName().'.isOnlineBooked'
                ])
                ->where(['<=', Holiday::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Holiday::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Holiday::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Holiday::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Holiday::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Holiday::tableName().'.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
            foreach ($bookingTypeWiseDataList as $bookingTypeData) {
                $bookingTypeWiseData[] = [
                    'bookingType' => ServiceConstant::BOOKING_TYPE[$bookingTypeData['isOnlineBooked']],
                    'qty' => $bookingTypeData['total'],
                    'totalQuote' => $bookingTypeData['quoteAmount'],
                    'totalReceived' => $bookingTypeData['receivedAmount'],
                    'totalDue' => ($bookingTypeData['quoteAmount'] - $bookingTypeData['receivedAmount']),
                    'netProfit' => $bookingTypeData['netProfit'],
                ];
            }
        }
        // Customer category and booking type wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY_BOOKING_TYPE', $reportTypes)) {
            $customerCategoryBookingTypeWiseDataList = Holiday::find()
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                    Holiday::tableName().'.customerCategory', Holiday::tableName().'.isOnlineBooked'
                ])
                ->where(['<=', Holiday::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Holiday::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Holiday::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Holiday::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Holiday::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Holiday::tableName().'.customerCategory', Holiday::tableName().'.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerCategoryBookingTypeWiseDataList as $categoryBookingTypeData) {
                $customerCategoryBookingTypeWiseData[] = [
                    'name' => $categoryBookingTypeData['customerCategory'] . ' ' . ServiceConstant::BOOKING_TYPE[$categoryBookingTypeData['isOnlineBooked']],
                    'qty' => $categoryBookingTypeData['total'],
                    'totalQuote' => $categoryBookingTypeData['quoteAmount'],
                    'totalReceived' => $categoryBookingTypeData['receivedAmount'],
                    'totalDue' => ($categoryBookingTypeData['quoteAmount'] - $categoryBookingTypeData['receivedAmount']),
                    'netProfit' => $categoryBookingTypeData['netProfit'],
                ];
            }
        }
        // Route wise report with date range
        if ($reportTypes && in_array('ROUTE', $reportTypes)) {
            $routingWiseDataList = Holiday::find()
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                    Holiday::tableName().'.route'
                ])
                ->where(['<=', Holiday::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Holiday::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Holiday::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Holiday::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Holiday::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Holiday::tableName().'.route'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
            foreach ($routingWiseDataList as $routeData) {
                $routingWiseData[] = [
                    'route' => $routeData['route'],
                    'qty' => $routeData['total'],
                    'totalQuote' => $routeData['quoteAmount'],
                    'totalReceived' => $routeData['receivedAmount'],
                    'totalDue' => ($routeData['quoteAmount'] - $routeData['receivedAmount']),
                    'netProfit' => $routeData['netProfit'],
                ];
            }
        }
        // Customer wise report with date range
        if ($reportTypes && in_array('CUSTOMER', $reportTypes)) {
            $customerWiseDataList = Holiday::find()
                ->joinWith(['customer'])
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                    Holiday::tableName().'.customerId'
                ])
                ->where(['<=', Holiday::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Holiday::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Holiday::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Holiday::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Holiday::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Holiday::tableName().'.customerId'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerWiseDataList as $customerData) {
                $customerWiseData[] = [
                    'name' => $customerData['customer']['name'],
                    'qty' => $customerData['total'],
                    'totalQuote' => $customerData['quoteAmount'],
                    'totalReceived' => $customerData['receivedAmount'],
                    'totalDue' => ($customerData['quoteAmount'] - $customerData['receivedAmount']),
                    'netProfit' => $customerData['netProfit'],
                ];
            }
        }
        // supplier wise report with date range
        if ($reportTypes && in_array('SUPPLIER', $reportTypes)) {
            $supplierWiseDataList = HolidaySupplier::find()
                ->leftJoin('holiday', Holiday::tableName().'.`id` = holiday_supplier.`holidayId`')
                ->leftJoin('supplier', 'supplier.`id` = holiday_supplier.`supplierId`')
                ->select([
                    new Expression('supplier.name as name'),
                    new Expression('supplier.company as company'),
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                    new Expression('SUM(holiday.netProfit) as netProfit'),
                    new Expression('SUM(holiday_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    'holiday_supplier.supplierId',
                ])
                ->where(['between', 'holiday_supplier.issueDate', $start_date, $end_date])
                ->andWhere([Holiday::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Holiday::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy(['holiday_supplier.supplierId'])
                ->orderBy('paidAmount DESC')
                ->asArray()
                ->all();

            foreach ($supplierWiseDataList as $supplierData) {
                $supplierWiseData[] = [
                    'name' => $supplierData['name'],
                    'qty' => $supplierData['total'],
                    'totalQuote' => $supplierData['quoteAmount'],
                    'totalReceived' => $supplierData['receivedAmount'],
                    'totalDue' => ($supplierData['quoteAmount'] - $supplierData['receivedAmount']),
                    'netProfit' => $supplierData['netProfit'],
                ];
            }
        }

        return $this->render('holiday-sales-report', [
            'date' => $date,
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'routingWiseData' => $routingWiseData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'supplierWiseData' => $supplierWiseData ?? [],
            'customerCategoryBookingTypeWiseData' => $customerCategoryBookingTypeWiseData ?? [],
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

        $reportTypes = Yii::$app->request->get('reportType');

        // Customer category wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY', $reportTypes)) {
            $customerCategoryWiseDataList = Hotel::find()
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                    'customerCategory'
                ])
                ->where(['<=', Hotel::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Hotel::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Hotel::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Hotel::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Hotel::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Hotel::tableName().'.customerCategory'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerCategoryWiseDataList as $categoryData) {
                $customerCategoryWiseData[] = [
                    'category' => $categoryData['customerCategory'],
                    'qty' => $categoryData['total'],
                    'totalQuote' => $categoryData['quoteAmount'],
                    'totalReceived' => $categoryData['receivedAmount'],
                    'totalDue' => ($categoryData['quoteAmount'] - $categoryData['receivedAmount']),
                    'netProfit' => $categoryData['netProfit'],
                ];
            }
        }
        // Booking type wise report
        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseDataList = Hotel::find()
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                    Hotel::tableName().'.isOnlineBooked'
                ])
                ->where(['<=', Hotel::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Hotel::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Hotel::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Hotel::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Hotel::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Hotel::tableName().'.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
            foreach ($bookingTypeWiseDataList as $bookingTypeData) {
                $bookingTypeWiseData[] = [
                    'bookingType' => ServiceConstant::BOOKING_TYPE[$bookingTypeData['isOnlineBooked']],
                    'qty' => $bookingTypeData['total'],
                    'totalQuote' => $bookingTypeData['quoteAmount'],
                    'totalReceived' => $bookingTypeData['receivedAmount'],
                    'totalDue' => ($bookingTypeData['quoteAmount'] - $bookingTypeData['receivedAmount']),
                    'netProfit' => $bookingTypeData['netProfit'],
                ];
            }
        }
        // Customer category and booking type wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY_BOOKING_TYPE', $reportTypes)) {
            $customerCategoryBookingTypeWiseDataList = Hotel::find()
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                    Hotel::tableName().'.customerCategory', Hotel::tableName().'.isOnlineBooked'
                ])
                ->where(['<=', Hotel::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Hotel::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Hotel::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Hotel::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Hotel::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Hotel::tableName().'.customerCategory', Hotel::tableName().'.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerCategoryBookingTypeWiseDataList as $categoryBookingTypeData) {
                $customerCategoryBookingTypeWiseData[] = [
                    'name' => $categoryBookingTypeData['customerCategory'] . ' ' . ServiceConstant::BOOKING_TYPE[$categoryBookingTypeData['isOnlineBooked']],
                    'qty' => $categoryBookingTypeData['total'],
                    'totalQuote' => $categoryBookingTypeData['quoteAmount'],
                    'totalReceived' => $categoryBookingTypeData['receivedAmount'],
                    'totalDue' => ($categoryBookingTypeData['quoteAmount'] - $categoryBookingTypeData['receivedAmount']),
                    'netProfit' => $categoryBookingTypeData['netProfit'],
                ];
            }
        }
        // Route wise report with date range
        if ($reportTypes && in_array('ROUTE', $reportTypes)) {
            $routingWiseDataList = Hotel::find()
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                    Hotel::tableName().'.route'
                ])
                ->where(['<=', Hotel::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Hotel::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Hotel::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Hotel::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Hotel::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Hotel::tableName().'.route'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
            foreach ($routingWiseDataList as $routeData) {
                $routingWiseData[] = [
                    'route' => $routeData['route'],
                    'qty' => $routeData['total'],
                    'totalQuote' => $routeData['quoteAmount'],
                    'totalReceived' => $routeData['receivedAmount'],
                    'totalDue' => ($routeData['quoteAmount'] - $routeData['receivedAmount']),
                    'netProfit' => $routeData['netProfit'],
                ];
            }
        }
        // Customer wise report with date range
        if ($reportTypes && in_array('CUSTOMER', $reportTypes)) {
            $customerWiseDataList = Hotel::find()
                ->joinWith(['customer'])
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                    Hotel::tableName().'.customerId'
                ])
                ->where(['<=', Hotel::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Hotel::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Hotel::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Hotel::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Hotel::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Hotel::tableName().'.customerId'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerWiseDataList as $customerData) {
                $customerWiseData[] = [
                    'name' => $customerData['customer']['name'],
                    'qty' => $customerData['total'],
                    'totalQuote' => $customerData['quoteAmount'],
                    'totalReceived' => $customerData['receivedAmount'],
                    'totalDue' => ($customerData['quoteAmount'] - $customerData['receivedAmount']),
                    'netProfit' => $customerData['netProfit'],
                ];
            }
        }
        // supplier wise report with date range
        if ($reportTypes && in_array('SUPPLIER', $reportTypes)) {
            $supplierWiseDataList = HotelSupplier::find()
                ->leftJoin('hotel', Hotel::tableName().'.`id` = hotel_supplier.`hotelId`')
                ->leftJoin('supplier', 'supplier.`id` = hotel_supplier.`supplierId`')
                ->select([
                    new Expression('supplier.name as name'),
                    new Expression('supplier.company as company'),
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                    new Expression('SUM(hotel.netProfit) as netProfit'),
                    new Expression('SUM(hotel_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    'hotel_supplier.supplierId',
                ])
                ->where(['between', 'hotel_supplier.issueDate', $start_date, $end_date])
                ->andWhere([Hotel::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Hotel::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy(['hotel_supplier.supplierId'])
                ->orderBy('paidAmount DESC')
                ->asArray()
                ->all();

            foreach ($supplierWiseDataList as $supplierData) {
                $supplierWiseData[] = [
                    'name' => $supplierData['name'],
                    'qty' => $supplierData['total'],
                    'totalQuote' => $supplierData['quoteAmount'],
                    'totalReceived' => $supplierData['receivedAmount'],
                    'totalDue' => ($supplierData['quoteAmount'] - $supplierData['receivedAmount']),
                    'netProfit' => $supplierData['netProfit'],
                ];
            }
        }

        return $this->render('hotel-sales-report', [
            'date' => $date,
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'supplierWiseData' => $supplierWiseData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'routeWiseData' => $routeWiseData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'customerCategoryBookingTypeWiseData' => $customerCategoryBookingTypeWiseData ?? [],
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

        $reportTypes = Yii::$app->request->get('reportType');
        // Customer category wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY', $reportTypes)) {
            $customerCategoryWiseDataList = Visa::find()
                ->select([
                    new Expression('COUNT(visa.id) as total'),
                    new Expression('SUM(visa.costOfSale) as costOfSale'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa.netProfit) as netProfit'),
                    'customerCategory'
                ])
                ->where(['<=', Visa::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Visa::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Visa::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Visa::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Visa::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Visa::tableName().'.customerCategory'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerCategoryWiseDataList as $categoryData) {
                $customerCategoryWiseData[] = [
                    'category' => $categoryData['customerCategory'],
                    'qty' => $categoryData['total'],
                    'totalQuote' => $categoryData['quoteAmount'],
                    'totalReceived' => $categoryData['receivedAmount'],
                    'totalDue' => ($categoryData['quoteAmount'] - $categoryData['receivedAmount']),
                    'netProfit' => $categoryData['netProfit'],
                ];
            }
        }
        // Booking type wise report
        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseDataList = Visa::find()
                ->select([
                    new Expression('COUNT(visa.id) as total'),
                    new Expression('SUM(visa.costOfSale) as costOfSale'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa.netProfit) as netProfit'),
                    Visa::tableName().'.isOnlineBooked'
                ])
                ->where(['<=', Visa::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Visa::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Visa::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Visa::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Visa::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Visa::tableName().'.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
            foreach ($bookingTypeWiseDataList as $bookingTypeData) {
                $bookingTypeWiseData[] = [
                    'bookingType' => ServiceConstant::BOOKING_TYPE[$bookingTypeData['isOnlineBooked']],
                    'qty' => $bookingTypeData['total'],
                    'totalQuote' => $bookingTypeData['quoteAmount'],
                    'totalReceived' => $bookingTypeData['receivedAmount'],
                    'totalDue' => ($bookingTypeData['quoteAmount'] - $bookingTypeData['receivedAmount']),
                    'netProfit' => $bookingTypeData['netProfit'],
                ];
            }
        }
        // Customer category and booking type wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY_BOOKING_TYPE', $reportTypes)) {
            $customerCategoryBookingTypeWiseDataList = Visa::find()
                ->select([
                    new Expression('COUNT(visa.id) as total'),
                    new Expression('SUM(visa.costOfSale) as costOfSale'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa.netProfit) as netProfit'),
                    Visa::tableName().'.customerCategory', Visa::tableName().'.isOnlineBooked'
                ])
                ->where(['<=', Visa::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Visa::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Visa::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Visa::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Visa::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Visa::tableName().'.customerCategory', Visa::tableName().'.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerCategoryBookingTypeWiseDataList as $categoryBookingTypeData) {
                $customerCategoryBookingTypeWiseData[] = [
                    'name' => $categoryBookingTypeData['customerCategory'] . ' ' . ServiceConstant::BOOKING_TYPE[$categoryBookingTypeData['isOnlineBooked']],
                    'qty' => $categoryBookingTypeData['total'],
                    'totalQuote' => $categoryBookingTypeData['quoteAmount'],
                    'totalReceived' => $categoryBookingTypeData['receivedAmount'],
                    'totalDue' => ($categoryBookingTypeData['quoteAmount'] - $categoryBookingTypeData['receivedAmount']),
                    'netProfit' => $categoryBookingTypeData['netProfit'],
                ];
            }
        }
        // Country wise report with date range
        if ($reportTypes && in_array('COUNTRY', $reportTypes)) {
            $countryWiseDataList = VisaSupplier::find()
                ->leftJoin('visa', 'visa.`id` = visa_supplier.`visaId`')
                ->leftJoin('country', 'country.`id` = visa_supplier.`countryId`')
                ->select([
                    new Expression('country.name as name'),
                    new Expression('country.code as code'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(visa_supplier.paidAmount) as paidAmount'),
                    'visa_supplier.countryId',
                ])
                ->where(['between', 'visa_supplier.issueDate', $start_date, $end_date])
                ->andWhere([Visa::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Visa::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy(['visa_supplier.countryId'])
                ->orderBy('quoteAmount DESC')
                ->asArray()
                ->all();

            foreach ($countryWiseDataList as $countryData) {
                $countryWiseData[] = [
                    'country' => $countryData['name'].'('.$countryData['code'].')',
                    'qty' => $countryData['total'],
                    'totalQuote' => $countryData['quoteAmount'],
                    'totalReceived' => $countryData['receivedAmount'],
                    'totalDue' => ($countryData['quoteAmount'] - $countryData['receivedAmount']),
                    'netProfit' => $countryData['netProfit'],
                ];
            }
        }
        // Customer wise report with date range
        if ($reportTypes && in_array('CUSTOMER', $reportTypes)) {
            $customerWiseDataList = Visa::find()
                ->joinWith(['customer'])
                ->select([
                    new Expression('customer.name as name'),
                    new Expression('COUNT(visa.id) as total'),
                    new Expression('SUM(visa.costOfSale) as costOfSale'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa.netProfit) as netProfit'),
                    Visa::tableName().'.customerId'
                ])
                ->where(['<=', Visa::tableName().'.refundRequestDate', $end_date])
                ->orWhere(['IS', Visa::tableName().'.refundRequestDate', NULL])
                ->andWhere(['between', Visa::tableName().'.issueDate', $start_date, $end_date])
                ->andWhere([Visa::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Visa::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy([Visa::tableName().'.customerId'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

            foreach ($customerWiseDataList as $customerData) {
                $customerWiseData[] = [
                    'name' => $customerData['name'],
                    'qty' => $customerData['total'],
                    'totalQuote' => $customerData['quoteAmount'],
                    'totalReceived' => $customerData['receivedAmount'],
                    'totalDue' => ($customerData['quoteAmount'] - $customerData['receivedAmount']),
                    'netProfit' => $customerData['netProfit'],
                ];
            }
        }
        // supplier wise report with date range
        if ($reportTypes && in_array('SUPPLIER', $reportTypes)) {
            $supplierWiseDataList = VisaSupplier::find()
                ->leftJoin('visa', Visa::tableName().'.`id` = visa_supplier.`visaId`')
                ->leftJoin('supplier', 'supplier.`id` = visa_supplier.`supplierId`')
                ->select([
                    new Expression('supplier.name as name'),
                    new Expression('supplier.company as company'),
                    new Expression('COUNT(visa.id) as total'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa.netProfit) as netProfit'),
                    new Expression('SUM(visa.netProfit) as netProfit'),
                    new Expression('SUM(visa_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(visa_supplier.paidAmount) as paidAmount'),
                    'visa_supplier.supplierId',
                ])
                ->where(['between', 'visa_supplier.issueDate', $start_date, $end_date])
                ->andWhere([Visa::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
                ->andWhere([Visa::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
                ->groupBy(['visa_supplier.supplierId'])
                ->orderBy('paidAmount DESC')
                ->asArray()
                ->all();

            foreach ($supplierWiseDataList as $supplierData) {
                $supplierWiseData[] = [
                    'name' => $supplierData['name'],
                    'qty' => $supplierData['total'],
                    'totalQuote' => $supplierData['quoteAmount'],
                    'totalReceived' => $supplierData['receivedAmount'],
                    'totalDue' => ($supplierData['quoteAmount'] - $supplierData['receivedAmount']),
                    'netProfit' => $supplierData['netProfit'],
                ];
            }
        }

        return $this->render('visa-sales-report', [
            'date' => $date,
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'customerCategoryBookingTypeWiseData' => $customerCategoryBookingTypeWiseData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'supplierWiseData' => $supplierWiseData ?? [],
            'countryWiseData' => $countryWiseData ?? [],
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->orderBy('total DESC')
            ->asArray()->one();

        // Monthly Package Report Data
        $monthlyPackageDataList = Holiday::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['<=', 'refundRequestDate', $monthly_end_date])
            ->orWhere(['IS', 'refundRequestDate', NULL])
            ->andWhere(['between', 'issueDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            ->groupBy(['type'])
            ->orderBy('total DESC')
            ->asArray()->all();

        $monthlyPackageRefundDataList = Holiday::find()
            ->select([
                new Expression('COUNT(id) as total'),
                new Expression('SUM(quoteAmount) as quoteAmount'),
                new Expression('SUM(costOfSale) as costOfSale'),
                new Expression('SUM(receivedAmount) as receivedAmount'),
                new Expression('SUM(netProfit) as netProfit'), 'type'])
            ->where(['between', 'refundRequestDate', $monthly_start_date, $monthly_end_date])
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
            ->andWhere(['<>', 'type', GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
            ->andWhere([Ticket::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
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
            ->andWhere(['type' => GlobalConstant::TICKET_TYPE_FOR_REFUND['Refund']])
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
