<?php

namespace app\modules\account\controllers;

use app\models\Company;
use app\modules\account\models\Bill;
use app\modules\account\models\search\BillSearch;
use app\controllers\ParentController;
use app\modules\account\models\Transaction;
use app\modules\account\repositories\BillRepository;
use app\modules\account\services\BillService;
use app\modules\account\services\RefundTransactionService;
use app\modules\sale\models\Supplier;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BillController implements the CRUD actions for Bill model.
 */
class BillController extends ParentController
{
    public BillService $billService;
    public RefundTransactionService $refundTransactionService;
    public BillRepository $billRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->refundTransactionService = new RefundTransactionService();
        $this->billService = new BillService();
        $this->billRepository = new BillRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Bill models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BillSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bill model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->billRepository->findOne(['uid' => $uid], Bill::class, ['supplier', 'details', 'transactions']),
            'company' => Company::findOne(['agencyId' => Yii::$app->user->identity->agencyId]),
        ]);
    }

    /**
     * Creates a new Bill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Bill();

        if ($this->request->isPost) {
            // Store ticket data
            $requestData = Yii::$app->request->post();
            $storeResponse = $this->billService->storeBill($requestData, $model);
            if (!$storeResponse['error']) {
                Yii::$app->session->setFlash('success', $storeResponse['message']);
                return $this->redirect(['view', 'uid' => $storeResponse['model']->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $storeResponse['message']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'transaction' => new Transaction(),
            'bankList' => $this->billService->getBankList()
        ]);
    }

    /**
     * Pay an existing Bill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPay(string $uid): mixed
    {
        $model = $this->billRepository->findOne(['uid' => $uid], Bill::class, ['details', 'supplier', 'transactions']);

        if ($model->dueAmount == 0) {
            Yii::$app->session->setFlash('danger', 'Invalid payment request!');
            return $this->redirect('index');
        }

        if (Yii::$app->request->isPost) {
            $billPaymentResponse = $this->billService->payment($model, Yii::$app->request->post());
            Yii::$app->session->setFlash($billPaymentResponse['error'] ? 'error' : 'success', $billPaymentResponse['message']);
            if (!$billPaymentResponse['error']) {
                return $this->render('view', [
                    'model' => $model,
                    'company' => Company::findOne(['agencyId' => Yii::$app->user->identity->agencyId]),
                ]);
            }
        }

        return $this->render('payment', [
            'model' => $model,
            'transaction' => new Transaction(),
            'refundList' => $this->refundTransactionService->getRefundList(Supplier::class, $model->supplierId),
            'bankList' => $this->billService->getBankList()
        ]);
    }

    /**
     * Deletes an existing Bill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Bill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Bill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Bill
    {
        if (($model = Bill::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionPending(): array
    {
        $data = Yii::$app->request->get();
        return $this->billService->getPendingBill($data);
    }
}
