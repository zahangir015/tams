<?php

namespace app\modules\sale\controllers;

use app\models\History;
use app\modules\sale\models\visa\RefundVisaSearch;
use app\modules\sale\models\visa\Visa;
use app\modules\sale\models\visa\VisaRefund;
use app\modules\sale\models\visa\VisaSupplier;
use app\modules\sale\models\visa\VisaSearch;
use app\controllers\ParentController;
use app\modules\sale\repositories\VisaRepository;
use app\modules\sale\services\VisaService;
use app\modules\sale\models\visa\VisaSupplierSearch;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * VisaController implements the CRUD actions for Visa model.
 */
class VisaController extends ParentController
{
    public VisaService $visaService;
    public VisaRepository $visaRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->visaService = new VisaService();
        $this->visaRepository = new VisaRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Visa models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new VisaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Hotel models.
     *
     * @return string
     */
    public function actionRefundList(): string
    {
        $searchModel = new RefundVisaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('refund_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all VisaSupplier models.
     *
     * @return string
     */
    public function actionVisaSupplierList(): string
    {
        $searchModel = new VisaSupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('visa_supplier_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Visa model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        $model = $this->visaRepository->findOne(['uid' => $uid], Visa::class,['visaSuppliers', 'customer']);
        return $this->render('view', [
            'model' => $model,
            'histories' => History::find()->where(['tableName' => Visa::tableName(), 'tableId' => $model->id])->orderBy(['id' => SORT_DESC])->all()
        ]);
    }

    /**
     * Creates a new Visa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Visa();
        $visaSupplier = new VisaSupplier();
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->visaService->storeVisa($requestData);
            Yii::$app->session->setFlash($response['error'] ? 'danger' : 'success', $response['message']);
            if (!$response['error']) {
                return $this->redirect(['view', 'uid' => $response['model']->uid]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'visaSupplier' => $visaSupplier,
        ]);
    }

    /**
     * Creates a new Visa model.
     * If creation is successful, the browser will be redirected to the 'refund list' page.
     * @return string|Response
     * @throws Exception
     */
    public function actionRefund(string $uid): Response|string
    {
        $model = new Visa();
        $motherVisa = $this->visaRepository->findOne(['uid' => $uid], Visa::class, ['visaSuppliers', 'invoice', 'customer']);
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->visaService->refundVisa($requestData, $motherVisa);
            Yii::$app->session->setFlash($response['error'] ? 'danger' : 'success', $response['message']);
            if ($response) {
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_form_refund', [
            'model' => $model,
            'motherVisa' => $motherVisa,
            'visaRefund' => new VisaRefund(),
        ]);
    }

    /**
     * Updates an existing Visa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->visaRepository->findOne(['uid' => $uid], Visa::class, ['visaSuppliers', 'customer', 'invoice']);

        if ($this->request->isPost) {
            // Update Visa
            $response = $this->visaService->updateVisa(Yii::$app->request->post(), $model);
            Yii::$app->session->setFlash($response['error'] ? 'danger' : 'success', $response['message']);
            if (!$response['error']) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Visa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $uid): Response
    {
        $this->findVisa($uid)->delete();

        return $this->redirect(['index']);
    }

    public function actionAddSupplier($row): string
    {
        $model = new Visa();
        $visaSupplier = new VisaSupplier();
        return $this->renderAjax('supplier', [
            'row' => $row,
            'model' => $model,
            'visaSupplier' => $visaSupplier,
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }
}
