<?php

namespace app\controllers;

use app\modules\admin\components\Helper;
use app\modules\hrm\services\AttendanceService;
use app\modules\sale\services\SaleService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        if (Helper::checkRoute('/site/sales-report/')) {
            $saleData = SaleService::dashboardReport();
            $totalQuantity = array_sum(array_column($saleData['currentDaySales'], 'total'));
            $totalQuote = array_sum(array_column($saleData['currentDaySales'], 'quoteAmount'));
            $totalReceived = array_sum(array_column($saleData['currentDaySales'], 'receivedAmount'));
            $totalPaid = array_sum(array_column($saleData['currentDaySales'], 'paidAmount'));
            $totalCost = array_sum(array_column($saleData['currentDaySales'], 'costOfSale'));
            $totalNerProfit = array_sum(array_column($saleData['currentDaySales'], 'netProfit'));

            $totalMonthlyQuantity = array_sum(array_column($saleData['currentMonthSales'], 'total'));
            $totalMonthlyQuote = array_sum(array_column($saleData['currentMonthSales'], 'quoteAmount'));
            $totalMonthlyReceived = array_sum(array_column($saleData['currentMonthSales'], 'receivedAmount'));
            $totalMonthlyPaid = array_sum(array_column($saleData['currentMonthSales'], 'paidAmount'));
            $totalMonthlyCost = array_sum(array_column($saleData['currentMonthSales'], 'costOfSale'));
            $totalMonthlyNerProfit = array_sum(array_column($saleData['currentMonthSales'], 'netProfit'));

            return $this->render('index', [
                'saleData' => $saleData,
                'totalQuantity' => $totalQuantity,
                'totalQuote' => $totalQuote,
                'totalReceived' => $totalReceived,
                'totalPaid' => $totalPaid,
                'totalCost' => $totalCost,
                'totalNetProfit' => $totalNerProfit,

                'totalMonthlyQuantity' => $totalMonthlyQuantity,
                'totalMonthlyQuote' => $totalMonthlyQuote,
                'totalMonthlyReceived' => $totalMonthlyReceived,
                'totalMonthlyPaid' => $totalMonthlyPaid,
                'totalMonthlyCost' => $totalMonthlyCost,
                'totalMonthlyNetProfit' => $totalMonthlyNerProfit,

                'ticketPercentage' => ($totalQuote) ? ($saleData['currentDaySales']['ticket']['quoteAmount'] * 100) / $totalQuote : 0,
                'hotelPercentage' => ($totalQuote) ? ($saleData['currentDaySales']['hotel']['quoteAmount'] * 100) / $totalQuote : 0,
                'holidayPercentage' => ($totalQuote) ? ($saleData['currentDaySales']['holiday']['quoteAmount'] * 100) / $totalQuote : 0,
                'visaPercentage' => ($totalQuote) ? ($saleData['currentDaySales']['visa']['quoteAmount'] * 100) / $totalQuote : 0,

                'monthlyTicketPercentage' => ($totalMonthlyQuote) ? ($saleData['currentMonthSales']['ticket']['quoteAmount'] * 100) / $totalMonthlyQuote : 0,
                'monthlyHotelPercentage' => ($totalMonthlyQuote) ? ($saleData['currentMonthSales']['hotel']['quoteAmount'] * 100) / $totalMonthlyQuote : 0,
                'monthlyHolidayPercentage' => ($totalMonthlyQuote) ? ($saleData['currentMonthSales']['holiday']['quoteAmount'] * 100) / $totalMonthlyQuote : 0,
                'monthlyVisaPercentage' => ($totalMonthlyQuote) ? ($saleData['currentMonthSales']['visa']['quoteAmount'] * 100) / $totalMonthlyQuote : 0,

                'receivable' => ($totalQuote - $totalReceived),
                'payable' => ($totalCost - $totalPaid),

                'monthlyReceivable' => ($totalMonthlyQuote - $totalMonthlyReceived),
                'monthlyPayable' => ($totalMonthlyCost - $totalMonthlyPaid),
            ]);
        }

        if (Helper::checkRoute('/site/attendance-report/')) {
            $leaveAttendanceData = AttendanceService::dashboardReport();//dd($leaveAttendanceData);
            return $this->render('index',
                [
                    'leaveAttendanceData' => $leaveAttendanceData,
                ]);
        }

        return $this->render('index');
    }

    public function actionSalesReport()
    {

    }

    public function actionAttendanceReport()
    {

    }
}
