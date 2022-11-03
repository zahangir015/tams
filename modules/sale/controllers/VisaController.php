<?php

namespace app\modules\sale\controllers;

use app\models\History;
use app\modules\sale\models\visa\Visa;
use app\modules\sale\models\visa\VisaSupplier;
use app\modules\sale\models\VisaSearch;
use app\controllers\ParentController;
use app\modules\sale\services\VisaService;
use app\modules\sale\models\visa\VisaSupplierSearch;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * VisaController implements the CRUD actions for Visa model.
 */
class VisaController extends ParentController
{
    public VisaService $visaService;

    public function __construct($uid, $module, $config = [])
    {
        $this->visaService = new VisaService();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Visa models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new VisaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Visa Supplier models.
     *
     * @return string
     */
    public function actionVisaSupplierList()
    {
        $searchModel = new VisaSupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('holiday_supplier_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'holidayCategories' => $this->holidayService->getCategories()
        ]);
    }

    /**
     * Displays a single Visa model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid)
    {
        $model = $this->visaService->findVisa($uid, ['visaSuppliers', 'customer']);
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
    public function actionCreate()
    {
        $model = new Visa();
        $visaSupplier = new VisaSupplier();
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->visaService->storeVisa($requestData);
            if ($response) {
                return $this->redirect('index');
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
     */
    public function actionRefund(string $uid)
    {
        $model = new Visa();
        $motherVisa = $this->visaService->findVisa($uid, ['visaSuppliers', 'invoice']);
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->visaService->refundVisa($requestData, $motherVisa);
            if ($response) {
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_refund', [
            'model' => $model,
            'motherVisa' => $motherVisa,
        ]);
    }

    /**
     * Updates an existing Visa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->visaService->findVisa($uid, ['visaSuppliers', 'customer']);

        if ($this->request->isPost) {
            // Update Visa
            $model = $this->visaService->updateVisa(Yii::$app->request->post(), $model);
            if ($model) {
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
    public function actionDelete(string $uid)
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
