<?php

namespace app\modules\account\controllers;

use app\modules\account\models\RefundTransaction;
use app\modules\account\models\search\RefundTransactionSearch;
use app\modules\account\models\Transaction;
use app\modules\account\repositories\RefundTransactionRepository;
use app\modules\account\services\RefundTransactionService;
use app\modules\hrm\repositories\EmployeeRepository;
use app\modules\hrm\services\EmployeeService;
use app\modules\hrm\services\HrmConfigurationService;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * RefundTransactionController implements the CRUD actions for RefundTransaction model.
 */
class RefundTransactionController extends Controller
{

    public RefundTransactionService $refundTransactionService;

    public function __construct($id, $module, $config = [])
    {
        $this->refundTransactionService = new RefundTransactionService();
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all RefundTransaction models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new RefundTransactionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RefundTransaction model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RefundTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new RefundTransaction();
        $transaction = new Transaction();
        if ($this->request->isPost) {
            $requestData = $this->request->post();
            $refundServiceProcessResponse = $this->refundTransactionService->storeRefundTransaction($requestData, $model, $transaction);dd($refundServiceProcessResponse, false);
            if ($model->load() && $model->save()) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'transaction' => $transaction,
        ]);
    }

    /**
     * Updates an existing RefundTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RefundTransaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $uid)
    {
        $this->findModel($uid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RefundTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return RefundTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RefundTransaction::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionCustomerPending(): array
    {
        $requestData = Yii::$app->request->get();
        Yii::$app->response->format = Response::FORMAT_JSON;

        $pendingServices = $this->refundTransactionService->customerPending($requestData);
        return [
            'html' => $pendingServices['html'],
            'totalPayable' => $pendingServices['totalPayable'],
            'totalReceivable' => $pendingServices['totalReceivable'],
        ];
    }
}
