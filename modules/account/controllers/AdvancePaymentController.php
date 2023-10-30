<?php

namespace app\modules\account\controllers;

use app\modules\account\models\AdvancePayment;
use app\modules\account\models\search\AdvancePaymentSearch;
use app\controllers\ParentController;
use app\modules\account\models\search\SupplierAdvancePaymentSearch;
use app\modules\account\models\Transaction;
use app\modules\account\repositories\InvoiceRepository;
use app\modules\account\services\AccountService;
use app\modules\account\services\InvoiceService;
use app\modules\account\services\RefundTransactionService;
use app\modules\sale\models\Customer;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AdvancePaymentController implements the CRUD actions for AdvancePayment model.
 */
class AdvancePaymentController extends ParentController
{
    public AccountService $accountService;

    public function __construct($uid, $module, $config = [])
    {
        $this->accountService = new AccountService();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all AdvancePayment models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new AdvancePaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSupplierAdvancePayment(): string
    {
        $searchModel = new SupplierAdvancePaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('supplier', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdvancePayment model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->findModel($uid),
        ]);
    }

    /**
     * Creates a new AdvancePayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new AdvancePayment();

        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $storeResponse = $this->accountService->storeAdvancePayment($requestData, $model);
            if ($storeResponse['error']) {
                Yii::$app->session->setFlash('danger', $storeResponse['message']);
            } else {
                Yii::$app->session->setFlash('success', $storeResponse['message']);
                return $this->redirect(['view', 'uid' => $storeResponse['data']->uid]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'transaction' => new Transaction(),
            'bankList' => $this->accountService->getBankList()
        ]);
    }

    /**
     * Updates an existing AdvancePayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'uid' => $model->uid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AdvancePayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $this->findModel($uid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdvancePayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return AdvancePayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): AdvancePayment
    {
        if (($model = AdvancePayment::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
