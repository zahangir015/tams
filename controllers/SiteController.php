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

    public function actionSourceSales()
    {
        if (Helper::checkRoute('/site/sales-report')) {
            $dataArray = SaleService::dashboardReport();
        }
    }

    public function actionServiceSales()
    {
        if (Helper::checkRoute('/site/sales-report')) {
            $monthlyServiceSale = SaleService::monthlySales();

            $saleData = [
                'currentDaySales' => [
                    'ticket' => $monthlyServiceSale['ticketSalesData'][date('Y-m-d')],
                    'hotel' => $monthlyServiceSale['hotelSalesData'][date('Y-m-d')],
                    'holiday' => $monthlyServiceSale['holidaySalesData'][date('Y-m-d')],
                    'visa' => $monthlyServiceSale['visaSalesData'][date('Y-m-d')],
                ],
                'currentMonthSales' => [
                    'ticket' => $monthlyServiceSale['ticketSalesData'][date('Y-m')],
                    'hotel' => $monthlyServiceSale['hotelSalesData'][date('Y-m')],
                    'holiday' => $monthlyServiceSale['holidaySalesData'][date('Y-m')],
                    'visa' => $monthlyServiceSale['visaSalesData'][date('Y-m')],
                ],
                'previousMonthSales' => [
                    'ticket' => $monthlyServiceSale['ticketSalesData'][date('Y-m', strtotime('-1 month'))],
                    'hotel' => $monthlyServiceSale['hotelSalesData'][date('Y-m', strtotime('-1 month'))],
                    'holiday' => $monthlyServiceSale['holidaySalesData'][date('Y-m', strtotime('-1 month'))],
                    'visa' => $monthlyServiceSale['visaSalesData'][date('Y-m', strtotime('-1 month'))],
                ],
            ];
        }
    }

    public function actionSalesDue()
    {
        if (Helper::checkRoute('/site/sales-report')) {
            $dataArray = SaleService::dashboardReport();
        }
    }

    public function actionSupplierSales()
    {
        if (Helper::checkRoute('/site/sales-report')) {
            $dataArray = SaleService::dashboardReport();
        }
    }

    public function actionSalesReport()
    {

    }

    public function actionAttendanceReport()
    {

    }
}
