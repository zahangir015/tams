<?php

namespace app\modules\sale\controllers;

use app\controllers\ParentController;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidaySupplier;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\hotel\HotelSupplier;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\models\visa\Visa;
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
        }

        $reportTypes = Yii::$app->request->get('reportType');

        if ($reportTypes && in_array('CUSTOMER_CATEGORY', $reportTypes)) {
            $customerCategoryWiseData = Ticket::find()
                ->joinWith(['ticketSupplier'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    'ticket.customerCategory',
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->groupBy(['ticket.customerCategory'])
                ->orderBy('total DESC')
                ->asArray()->all();
        }

        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseData = Ticket::find()
                ->joinWith(['ticketSupplier'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    'ticket.bookedOnline'
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->groupBy(['ticket.bookedOnline'])
                ->orderBy('total DESC')
                ->asArray()->all();
        }

        if ($reportTypes && in_array('FLIGHT_TYPE', $reportTypes)) {
            $flightTypeWiseData = Ticket::find()
                ->joinWith(['ticketSupplier'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    'ticket.flightType'
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->groupBy(['ticket.flightType'])
                ->orderBy('total DESC')
                ->asArray()->all();
        }

        if ($reportTypes && in_array('PROVIDER', $reportTypes)) {
            $providerWiseData = Ticket::find()
                ->joinWith(['ticketSupplier', 'provider'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    'ticket.providerId'
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->groupBy(['ticket.providerId'])
                ->orderBy('total DESC')
                ->asArray()->all();
        }

        if ($reportTypes && in_array('AIRLINES', $reportTypes)) {
            $airlineWiseData = Ticket::find()
                ->joinWith(['ticketSupplier', 'airline'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    'ticket.airlineId'
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->groupBy(['ticket.airlineId'])
                ->orderBy('total DESC')
                ->asArray()->all();
        }

        if ($reportTypes && in_array('ROUTING', $reportTypes)) {
            $routingWiseData = Ticket::find()
                ->joinWith(['ticketSupplier', 'airline'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    'ticket.route'
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->groupBy(['ticket.route'])
                ->orderBy(['total' => SORT_DESC])
                ->asArray()->all();
        }

        if ($reportTypes && in_array('SUPPLIER', $reportTypes)) {
            $supplierWiseData = TicketSupplier::find()
                ->leftJoin('ticket', 'ticket.`id` = ticket_supplier.`ticketId`')
                ->leftJoin('supplier', 'supplier.`id` = ticket_supplier.`supplierId`')
                ->select([
                    new Expression('supplier.name as name'),
                    new Expression('supplier.company as company'),
                    new Expression('sum(ticket.numberOfSegment) as numberOfSegment'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    'ticket_supplier.supplierId'
                ])
                ->where(['between', 'ticket_supplier.issueDate', $start_date, $end_date])
                ->groupBy(['ticket_supplier.supplierId'])
                ->orderBy('numberOfSegment DESC')
                ->asArray()->all();
        }

        if ($reportTypes && in_array('CUSTOMER', $reportTypes)) {
            $customerWiseData = Ticket::find()
                ->joinWith(['ticketSupplier', 'customer'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    'ticket.customerId'
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->groupBy(['ticket.customerId'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        // Customer category and booking type wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY_BOOKING_TYPE', $reportTypes)) {
            $customerCategoryBookingTypeWiseData = Ticket::find()
                ->joinWith(['ticketSupplier', 'customer'])
                ->select([
                    new Expression('COUNT(ticket.id) as total'),
                    new Expression('SUM(ticket.baseFare) as baseFare'),
                    new Expression('SUM(ticket.tax) as tax'),
                    new Expression('SUM(ticket.otherTax) as otherTax'),
                    new Expression('SUM(ticket.quoteAmount) as quoteAmount'),
                    new Expression('SUM(ticket.receivedAmount) as receivedAmount'),
                    new Expression('SUM(ticket_supplier.paidAmount) as paidAmount'),
                    new Expression('SUM(ticket.costOfSale) as costOfSale'),
                    new Expression('SUM(ticket.netProfit) as netProfit'),
                    'ticket.customerCategory', 'ticket.bookedOnline'
                ])
                ->where(['<=', 'ticket.refundRequestDate', $end_date])
                ->orWhere(['IS', 'ticket.refundRequestDate', NULL])
                ->andWhere(['between', 'ticket.issueDate', $start_date, $end_date])
                ->groupBy(['customerCategory', 'bookedOnline'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        return $this->render('ticket-sales-report', [
            'date' => $date,
            'supplierWiseData' => $supplierWiseData ?? [],
            'routingWiseData' => $routingWiseData ?? [],
            'flightTypeWiseData' => $flightTypeWiseData ?? [],
            'customerWiseData' => $customerWiseData ?? [],
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'providerWiseData' => $providerWiseData ?? [],
            'airlineWiseData' => $airlineWiseData ?? [],
            'customerCategoryBookingTypeWiseData' => $customerCategoryBookingTypeWiseData ?? [],
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
            $customerCategoryWiseData = Holiday::find()
                ->joinWith(['holidaySupplier'])
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    'customerCategory'
                ])
                ->where(['<=', 'holiday.refundRequestDate', $end_date])
                ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
                ->andWhere(['between', 'holiday.issueDate', $start_date, $end_date])
                ->groupBy(['holiday.customerCategory'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }
        // Booking type wise report
        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseData = Holiday::find()
                ->joinWith(['holidaySupplier'])
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    'holiday.bookedOnline'
                ])
                ->where(['<=', 'holiday.refundRequestDate', $end_date])
                ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
                ->andWhere(['between', 'holiday.issueDate', $start_date, $end_date])
                ->groupBy(['holiday.bookedOnline'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }
        // Route wise report with date range
        if ($reportTypes && in_array('ROUTE', $reportTypes)) {
            $routeWiseDataList = Holiday::find()
                ->joinWith(['holidaySupplier'])
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    'holiday.route'
                ])
                ->where(['<=', 'holiday.refundRequestDate', $end_date])
                ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
                ->andWhere(['between', 'holiday.issueDate', $start_date, $end_date])
                ->groupBy(['holiday.route'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }
        // Customer wise report with date range
        if ($reportTypes && in_array('CUSTOMER', $reportTypes)) {
            $customerWiseDataList = Holiday::find()
                ->joinWith(['holidaySupplier', 'customer'])
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    'holiday.customerId'
                ])
                ->where(['<=', 'holiday.refundRequestDate', $end_date])
                ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
                ->andWhere(['between', 'holiday.issueDate', $start_date, $end_date])
                ->groupBy(['holiday.customerId'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }
        // supplier wise report with date range
        if ($reportTypes && in_array('SUPPLIER', $reportTypes)) {
            $supplierWiseData = HolidaySupplier::find()
                ->leftJoin('holiday', 'holiday.`id` = holiday_supplier.`holidayId`')
                ->leftJoin('supplier', 'supplier.`id` = holiday_supplier.`supplierId`')
                ->select([
                    new Expression('supplier.name as name'),
                    new Expression('supplier.company as company'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    'holiday_supplier.supplierId',
                ])
                ->where(['between', 'holiday_supplier.issueDate', $start_date, $end_date])
                ->groupBy(['holiday_supplier.supplierId'])
                ->orderBy('paidAmount DESC')
                ->asArray()
                ->all();
        }

        // Customer category and booking type wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY_BOOKING_TYPE', $reportTypes)) {
            $customerCategoryBookingTypeWiseData = Holiday::find()
                ->joinWith(['holidaySupplier'])
                ->select([
                    new Expression('COUNT(holiday.id) as total'),
                    new Expression('SUM(holiday.costOfSale) as costOfSale'),
                    new Expression('SUM(holiday.quoteAmount) as quoteAmount'),
                    new Expression('SUM(holiday.receivedAmount) as receivedAmount'),
                    new Expression('SUM(holiday_supplier.paidAmount) as paidAmount'),
                    'holiday.customerCategory', 'holiday.bookedOnline'
                ])
                ->where(['<=', 'holiday.refundRequestDate', $end_date])
                ->orWhere(['IS', 'holiday.refundRequestDate', NULL])
                ->andWhere(['between', 'holiday.issueDate', $start_date, $end_date])
                ->groupBy(['holiday.customerCategory', 'holiday.bookedOnline'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();

        }


        return $this->render('package-sales-report', [
            'date' => $date,
            'customerCategoryWiseData' => $customerCategoryWiseData ?? [],
            'bookingTypeWiseData' => $bookingTypeWiseData ?? [],
            'routeWiseData' => $routeWiseData ?? [],
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
            $customerCategoryWiseData = Hotel::find()
                ->joinWith(['hotelSupplier'])
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.totalNights) as totalNights'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    'hotel.customerCategory'
                ])
                ->where(['<=', 'hotel.refundRequestDate', $end_date])
                ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
                ->andWhere(['between', 'hotel.issueDate', $start_date, $end_date])
                ->groupBy(['hotel.customerCategory'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        // Booking type wise report with date range
        if ($reportTypes && in_array('BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseData = Hotel::find()
                ->joinWith(['hotelSupplier'])
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.totalNights) as totalNights'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    'hotel.isOnlineBooked'
                ])
                ->where(['<=', 'hotel.refundRequestDate', $end_date])
                ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
                ->andWhere(['between', 'hotel.issueDate', $start_date, $end_date])
                ->groupBy(['hotel.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        // Customer category and booking type wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY_BOOKING_TYPE', $reportTypes)) {
            $customerCategoryBookingTypeWiseData = Hotel::find()
                ->joinWith(['hotelSupplier'])
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.totalNights) as totalNights'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    'hotel.customerCategory', 'hotel.isOnlineBooked'
                ])
                ->where(['<=', 'hotel.refundRequestDate', $end_date])
                ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
                ->andWhere(['between', 'hotel.issueDate', $start_date, $end_date])
                ->groupBy(['hotel.customerCategory', 'hotel.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        // Route wise report with date range
        if ($reportTypes && in_array('ROUTE', $reportTypes)) {
            $routeWiseData = Hotel::find()
                ->joinWith(['hotelSupplier'])
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.totalNights) as totalNights'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    'hotel.route'
                ])
                ->where(['<=', 'hotel.refundRequestDate', $end_date])
                ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
                ->andWhere(['between', 'hotel.issueDate', $start_date, $end_date])
                ->groupBy(['hotel.route'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        // Customer wise report with date range
        if ($reportTypes && in_array('CUSTOMER', $reportTypes)) {
            $customerWiseData = Hotel::find()
                ->joinWith(['hotelSupplier', 'customer'])
                ->select([
                    new Expression('COUNT(hotel.id) as total'),
                    new Expression('SUM(hotel.totalNights) as totalNights'),
                    new Expression('SUM(hotel.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel.receivedAmount) as receivedAmount'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    'hotel.customerId'
                ])
                ->where(['<=', 'hotel.refundRequestDate', $end_date])
                ->orWhere(['IS', 'hotel.refundRequestDate', NULL])
                ->andWhere(['between', 'hotel.issueDate', $start_date, $end_date])
                ->groupBy(['hotel.customerId'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        // supplier wise report with date range
        if ($reportTypes && in_array('SUPPLIER', $reportTypes)) {
            $supplierWiseData = HotelSupplier::find()
                ->leftJoin('hotel', 'hotel.`id` = hotel_supplier.`hotelId`')
                ->leftJoin('supplier', 'supplier.`id` = hotel_supplier.`supplierId`')
                ->select([
                    new Expression('supplier.name as name'),
                    new Expression('supplier.company as company'),
                    new Expression('SUM(hotel.quoteAmount) as quoteAmount'),
                    new Expression('SUM(hotel_supplier.costOfSale) as costOfSale'),
                    new Expression('SUM(hotel_supplier.paidAmount) as paidAmount'),
                    'hotel_supplier.supplierId',
                ])
                ->where(['between', 'hotel_supplier.issueDate', $start_date, $end_date])
                ->groupBy(['hotel_supplier.supplierId'])
                ->orderBy('paidAmount DESC')
                ->asArray()
                ->all();
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
            $customerCategoryWiseData = Visa::find()
                ->joinWith(['hotelSupplier'])
                ->select([
                    new Expression('SUM(visa.totalQuantity) as total'),
                    new Expression('SUM(visa.costOfSale) as costOfSale'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa_supplier.paidAmount) as paidAmount'),
                    'visa.customerCategory'
                ])
                ->where(['<=', 'visa.refundRequestDate', $end_date])
                ->orWhere(['IS', 'visa.refundRequestDate', NULL])
                ->andWhere(['between', 'visa.issueDate', $start_date, $end_date])
                ->groupBy(['visa.customerCategory'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        // Customer category and booking type wise report with date range
        if ($reportTypes && in_array('CUSTOMER_CATEGORY_BOOKING_TYPE', $reportTypes)) {
            $bookingTypeWiseDataList = Visa::find()
                ->joinWith(['hotelSupplier'])
                ->select([
                    new Expression('SUM(visa.totalQuantity) as total'),
                    new Expression('SUM(visa.costOfSale) as costOfSale'),
                    new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                    new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                    new Expression('SUM(visa_supplier.paidAmount) as paidAmount'),
                    'visa.customerCategory', 'visa.isOnlineBooked'
                ])
                ->where(['<=', 'visa.refundRequestDate', $end_date])
                ->orWhere(['IS', 'visa.refundRequestDate', NULL])
                ->andWhere(['between', 'visa.issueDate', $start_date, $end_date])
                ->groupBy(['visa.customerCategory', 'visa.isOnlineBooked'])
                ->orderBy('total DESC')
                ->asArray()
                ->all();
        }

        // Route wise report with date range
        $routeWiseDataList = Visa::find()
            ->joinWith(['hotelSupplier'])
            ->select([
                new Expression('SUM(visa.totalQuantity) as total'),
                new Expression('SUM(visa.costOfSale) as costOfSale'),
                new Expression('SUM(visa.quoteAmount) as quoteAmount'),
                new Expression('SUM(visa.receivedAmount) as receivedAmount'),
                new Expression('SUM(visa_supplier.paidAmount) as paidAmount'),
                'visa.customerCategory', 'visa.isOnlineBooked'
            ])
            ->where(['<=', 'visa.refundRequestDate', $end_date])
            ->orWhere(['IS', 'visa.refundRequestDate', NULL])
            ->andWhere(['between', 'visa.issueDate', $start_date, $end_date])
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
