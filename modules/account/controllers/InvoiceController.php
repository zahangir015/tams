<?php

namespace app\modules\account\controllers;

use app\components\Constant;
use app\models\Company;
use app\modules\account\models\Invoice;
use app\modules\account\models\search\InvoiceSearch;
use app\controllers\ParentController;
use app\modules\account\models\Transaction;
use app\modules\account\models\TransactionStatement;
use app\modules\account\repositories\InvoiceRepository;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\RefundTransactionService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends ParentController
{
    public InvoiceService $invoiceService;
    public RefundTransactionService $refundTransactionService;
    public InvoiceRepository $invoiceRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->refundTransactionService = new RefundTransactionService();
        $this->invoiceService = new InvoiceService();
        $this->invoiceRepository = new InvoiceRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Invoice models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid)
    {
        return $this->render('view', [
            'model' => $this->invoiceRepository->findOne(['uid' => $uid], Invoice::class, ['details', 'customer', 'transactions']),
            'company' => Company::findOne(['id' => 1]),
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Invoice();

        if ($this->request->isPost) {
            // Store ticket data
            $requestData = Yii::$app->request->post();
            $storeResponse = $this->invoiceService->storeInvoice($requestData, $model);
            if ($storeResponse) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($uid): Response
    {
        $this->findModel($uid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Payment an existing Invoices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid
     * @return mixed
     * @throws Exception
     */
    public function actionPay(string $uid): mixed
    {
        $model = $this->invoiceRepository->findOne(['uid' => $uid], Invoice::class, ['details', 'customer', 'transactions']);

        if ($model->dueAmount == 0) {
            Yii::$app->session->setFlash('danger', 'Invalid payment request');
            return $this->redirect('index');
        }

        $transaction = new Transaction();
        if (Yii::$app->request->isPost) {
            $invoicePaymentResponse = $this->invoiceService->payment($model, Yii::$app->request->post());
            Yii::$app->session->setFlash($invoicePaymentResponse['error'] ? 'error' : 'success', $invoicePaymentResponse['message']);
            if (!$invoicePaymentResponse['error']) {
                return $this->render('view', [
                    'model' => $model,
                    'company' => Company::findOne(['id' => 1]),
                ]);
            }
        }

        return $this->render('payment', [
            'model' => $model,
            'transaction' => $transaction,
            'refundList' => $this->refundTransactionService->getRefundList(Customer::class, $model->customerId),
            'bankList' => $this->invoiceService->getBankList()
        ]);
    }

    public function actionPending(): array
    {
        $data = Yii::$app->request->get();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $customerId = $data['customerId'];
        if (empty($customerId)) {
            return false;
        }

        $start_date = $end_date = null;
        if (isset($data['dateRange']) && strpos($data['dateRange'], '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $data['dateRange']);
        }

        $pendingServices = Customer::find()
            ->select(['id', 'name', 'company'])
            ->with([
                'tickets' => function ($query) use ($start_date, $end_date) {
                    $query
                        ->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'invoiceId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'visas' => function ($query) use ($start_date, $end_date) {
                    $query->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'invoiceId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'hotels' => function ($query) use ($start_date, $end_date) {
                    $query->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'invoiceId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'holidays' => function ($query) use ($start_date, $end_date) {
                    $query->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'invoiceId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
            ])
            ->where(['id' => $customerId])->one();

        $html = '';
        $key = 1;
        $totalPayable = 0;
        if (!empty($pendingServices->tickets)) {
            foreach ($pendingServices->tickets as $pending) {
                $totalPayable += ($pending->quoteAmount - $pending->receivedAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->receivedAmount,
                        'dueAmount' => ($pending->quoteAmount - $pending->receivedAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->eTicket . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->quoteAmount - $pending->receivedAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->quoteAmount - $pending->receivedAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->hotels)) {
            foreach ($pendingServices->hotels as $pending) {
                $totalPayable += ($pending->quoteAmount - $pending->receivedAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->receivedAmount,
                        'dueAmount' => ($pending->quoteAmount - $pending->receivedAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->voucherId . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->quoteAmount - $pending->receivedAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->quoteAmount - $pending->receivedAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->visas)) {
            foreach ($pendingServices->visas as $pending) {
                $totalPayable += ($pending->quoteAmount - $pending->receivedAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->receivedAmount,
                        'dueAmount' => ($pending->quoteAmount - $pending->receivedAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->identificationNo ?? 'N/A' . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->quoteAmount - $pending->receivedAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->quoteAmount - $pending->receivedAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->holidays)) {
            foreach ($pendingServices->holidays as $pending) {
                $totalPayable += ($pending->quoteAmount - $pending->receivedAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->receivedAmount,
                        'dueAmount' => ($pending->quoteAmount - $pending->receivedAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->identificationNo ?? 'NA' . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->quoteAmount - $pending->receivedAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->quoteAmount - $pending->receivedAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }

        return [
            'html' => $html,
            'totalPayable' => $totalPayable,
        ];
    }
}
