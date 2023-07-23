<?php

namespace app\controllers;

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
        $saleData = SaleService::dashboardReport();
        $leaveAttendanceData = AttendanceService::dashboardReport();//dd($leaveAttendanceData);
        $totalQuote = array_sum(array_column($saleData, 'quoteAmount'));
        $totalReceived = array_sum(array_column($saleData, 'receivedAmount'));
        $totalPaid = array_sum(array_column($saleData, 'paidAmount'));
        $totalCost = array_sum(array_column($saleData, 'costOfSale'));
        return $this->render('index', [
            'saleData' => $saleData,
            'totalQuote' => $totalQuote,
            'totalReceived' => $totalReceived,
            'totalPaid' => $totalPaid,
            'totalCost' => $totalCost,
            'ticketPercentage' => ($totalQuote) ? ($saleData['ticketSalesData']['quoteAmount'] * 100) / $totalQuote : 0,
            'hotelPercentage' => ($totalQuote) ? ($saleData['hotelSalesData']['quoteAmount'] * 100) / $totalQuote : 0,
            'holidayPercentage' => ($totalQuote) ? ($saleData['holidaySalesData']['quoteAmount'] * 100) / $totalQuote : 0,
            'visaPercentage' => ($totalQuote) ? ($saleData['visaSalesData']['quoteAmount'] * 100) / $totalQuote : 0,
            'receivable' => ($totalQuote - $totalReceived),
            'payable' => ($totalCost - $totalPaid),
            'leaveAttendanceData' => $leaveAttendanceData,
        ]);
    }
}
