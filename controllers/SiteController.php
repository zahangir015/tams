<?php

namespace app\controllers;

use app\components\GlobalConstant;
use app\modules\admin\components\Helper;
use app\modules\hrm\services\AttendanceService;
use app\modules\sale\components\ServiceConstant;
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
        $dataArray = [];

        if (Helper::checkRoute('/site/sales-report')) {
            $dataArray = SaleService::dashboardReport();
        }

        if (Helper::checkRoute('/site/attendance-report')) {
            $dataArray['leaveAttendanceData'] = AttendanceService::dashboardReport();
        }
        //dd([Helper::checkRoute('/site/sales-report'), Helper::checkRoute('/site/attendance-report')], false);
        return $this->render('index', $dataArray);
    }

    public function actionSourceSale()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Helper::checkRoute('/site/sales-report')) {
            $topSaleSourceTicketSalesData = SaleService::sourceSale();
            $data = [];
            $totalQuote = array_sum(array_column($topSaleSourceTicketSalesData, 'quoteAmount'));
            $totalSource = count($topSaleSourceTicketSalesData);
            foreach ($topSaleSourceTicketSalesData as $key => $singleSource) {
                $data['labels'][] = ServiceConstant::BOOKING_TYPE[$singleSource['bookedOnline']];
                $data['percentage'][] = ($totalQuote) ? ($singleSource['quoteAmount'] * 100) / $totalQuote : 0;
                $data['colorCodes'][] = GlobalConstant::CHART_COLOR_CODE[$key];
            }

            return $data;
        }
    }

    public function actionServiceSales()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Helper::checkRoute('/site/sales-report')) {
            $monthlyServiceSale = SaleService::monthlySales();
            $currentMonthSales = [
                'ticket' => $monthlyServiceSale[date('Y-m')]['ticket'],
                'hotel' => $monthlyServiceSale[date('Y-m')]['hotel'],
                'holiday' => $monthlyServiceSale[date('Y-m')]['holiday'],
                'visa' => $monthlyServiceSale[date('Y-m')]['visa'],
            ];

            $totalMonthlyQuote = array_sum(array_column($currentMonthSales, 'quoteAmount'));
            $monthlyHolidayPercentage = ($totalMonthlyQuote) ? ($currentMonthSales['holiday']['quoteAmount'] * 100) / $totalMonthlyQuote : 0;
            $monthlyTicketPercentage = ($totalMonthlyQuote) ? ($currentMonthSales['ticket']['quoteAmount'] * 100) / $totalMonthlyQuote : 0;
            $monthlyHotelPercentage = ($totalMonthlyQuote) ? ($currentMonthSales['hotel']['quoteAmount'] * 100) / $totalMonthlyQuote : 0;
            $monthlyVisaPercentage = ($totalMonthlyQuote) ? ($currentMonthSales['visa']['quoteAmount'] * 100) / $totalMonthlyQuote : 0;

            return [$monthlyHolidayPercentage, $monthlyTicketPercentage, $monthlyHotelPercentage, $monthlyVisaPercentage];
        }
    }

    public function actionSalesDue()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Helper::checkRoute('/site/sales-report')) {
            $monthlyServiceSale = SaleService::monthlySales();
            unset($monthlyServiceSale[date('Y-m-d')]);
            $data = [];
            foreach ($monthlyServiceSale as $key => $saleData){
                $data['sales'][] = array_sum(array_column($saleData, 'quoteAmount'));
                $data['due'][] = array_sum(array_column($saleData, 'quoteAmount')) - array_sum(array_column($saleData, 'receivedAmount'));
                $data['profitLoss'][] = array_sum(array_column($saleData, 'netProfit'));
            }
            return $data;
        }
    }

    public function actionSupplierSales()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Helper::checkRoute('/site/sales-report')) {
            $topSupplierTicketSalesData = SaleService::supplierSales();
            $totalCost = array_sum(array_column($topSupplierTicketSalesData, 'costOfSale'));
            $data = [];
            $colorCode = 0;
            foreach ($topSupplierTicketSalesData as $key => $datum){
                $data['labels'][] = $key;
                $data['percentage'][] = ($totalCost) ? ($datum['costOfSale'] * 100) / $totalCost : 0;
                $data['colorCodes'][] = GlobalConstant::CHART_COLOR_CODE[$colorCode];
                $colorCode++;
            }
            return $data;
        }
    }

    public function actionSalesReport()
    {

    }

    public function actionAttendanceReport()
    {

    }
}
