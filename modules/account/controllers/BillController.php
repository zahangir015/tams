<?php

namespace app\modules\account\controllers;

use app\modules\account\models\Bill;
use app\modules\account\models\search\BillSearch;
use app\controllers\ParentController;
use app\modules\account\models\Transaction;
use app\modules\account\repositories\BillRepository;
use app\modules\account\services\BillService;
use app\modules\account\services\RefundTransactionService;
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
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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
            if ($storeResponse) {
                return $this->redirect(['view', 'uid' => $model->uid]);
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
     * Updates an existing Bill model.
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
     * Deletes an existing Bill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
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
    protected function findModel($id)
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
