<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\models\History;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidayCategory;
use app\modules\sale\models\holiday\HolidayRefund;
use app\modules\sale\models\holiday\HolidaySearch;
use app\controllers\ParentController;
use app\modules\sale\models\holiday\HolidaySupplierSearch;
use app\modules\sale\models\holiday\RefundHolidaySearch;
use app\modules\sale\services\HolidayService;
use app\modules\sale\models\holiday\HolidaySupplier;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * HolidayController implements the CRUD actions for Holiday model.
 */
class HolidayController extends ParentController
{
    public HolidayService $holidayService;

    public function __construct($uid, $module, $config = [])
    {
        $this->holidayService = new HolidayService();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Holiday models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new HolidaySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'holidayCategories' => $this->holidayService->getCategories()
        ]);
    }

    /**
     * Lists all Holiday Supplier models.
     *
     * @return string
     */
    public function actionHolidaySupplierList(): string
    {
        $searchModel = new HolidaySupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('holiday_supplier_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'holidayCategories' => $this->holidayService->getCategories()
        ]);
    }

    /**
     * Lists all Holiday models.
     *
     * @return string
     */
    public function actionRefundList(): string
    {
        $searchModel = new RefundHolidaySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('refund_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'holidayCategories' => $this->holidayService->getCategories()
        ]);
    }

    /**
     * Displays a single Holiday model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        $model = $this->holidayService->findHoliday($uid, ['holidaySuppliers', 'customer', 'holidayCategory']);
        return $this->render('view', [
            'model' => $model,
            'histories' => History::find()->where(['tableName' => Holiday::tableName(), 'tableId' => $model->id])->orderBy(['id' => SORT_DESC])->all()
        ]);
    }

    /**
     * Creates a new Holiday model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Holiday();
        $holidaySupplier = new HolidaySupplier();
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->holidayService->storeHoliday($requestData);
            if ($response) {
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'holidaySupplier' => $holidaySupplier,
            'holidayCategories' => $this->holidayService->getCategories()
        ]);
    }

    /**
     * Creates a new Holiday model.
     * If creation is successful, the browser will be redirected to the 'refund list' page.
     * @return string|Response
     */
    public function actionRefund(string $uid): Response|string
    {
        $model = new Holiday();
        $motherHoliday = $this->holidayService->findHoliday($uid, ['holidaySuppliers', 'holidayCategory', 'invoice']);
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->holidayService->refundHoliday($requestData, $motherHoliday);
            if ($response) {
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('refund', [
            'model' => $model,
            'motherHoliday' => $motherHoliday,
            'holidayRefund' => new HolidayRefund(),
            'holidayCategories' => $this->holidayService->getCategories()
        ]);
    }

    /**
     * Updates an existing Holiday model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->holidayService->findHoliday($uid, ['holidaySuppliers', 'customer', 'holidayCategory']);

        if ($this->request->isPost) {
            // Update Holiday
            $updateResponse = $this->holidayService->updateRefundHoliday(Yii::$app->request->post(), $model);
            if ($updateResponse['error']) {
                Yii::$app->session->setFlash('danger', $updateResponse['message']);
            } else {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'holidayCategories' => $this->holidayService->getCategories()
        ]);
    }


    /**
     * Updates an existing Holiday model for refund.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionRefundUpdate(string $uid): Response|string
    {
        $model = $this->holidayService->findHoliday($uid, ['holidaySuppliers', 'customer', 'holidayCategory', 'holidayRefund']);
        //dd($model->holidayRefund);
        if ($this->request->isPost) {
            // Update Holiday
            $model = $this->holidayService->updateHoliday(Yii::$app->request->post(), $model);
            if ($model) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        }

        return $this->render('refund_update', [
            'model' => $model,
            'holidayRefund' => $model->holidayRefund,
            'holidayCategories' => $this->holidayService->getCategories()
        ]);
    }

    /**
     * Deletes an existing Holiday model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $uid)
    {
        $model = $this->findModel($uid);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionAddSupplier($row): string
    {
        $model = new Holiday();
        $holidaySupplier = new HolidaySupplier();
        $holidayCategories = ArrayHelper::map(HolidayCategory::findAll(['status' => GlobalConstant::ACTIVE_STATUS]), 'id', 'name');
        return $this->renderAjax('supplier', [
            'row' => $row,
            'model' => $model,
            'holidaySupplier' => $holidaySupplier,
            'holidayCategories' => $holidayCategories,
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }
}
