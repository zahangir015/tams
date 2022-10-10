<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidayCategory;
use app\modules\sale\models\holiday\HolidaySearch;
use app\controllers\ParentController;
use app\modules\sale\services\HolidayService;
use app\modules\sale\models\holiday\HolidaySupplier;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
    public function actionIndex()
    {
        $searchModel = new HolidaySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Holiday model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid)
    {
        return $this->render('view', [
            'model' => $this->holidayService->findHoliday($uid),
        ]);
    }

    /**
     * Creates a new Holiday model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Holiday();
        $holidaySupplier = new HolidaySupplier();
        $holidayCategories = ArrayHelper::map(HolidayCategory::findAll(['status' => GlobalConstant::ACTIVE_STATUS]), 'id', 'name');
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = HolidayService::storeHoliday($requestData);
            if ($response) {
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'holidaySupplier' => $holidaySupplier,
            'holidayCategories' => $holidayCategories
        ]);
    }

    /**
     * Updates an existing Holiday model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|\yii\web\Response
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
     * Deletes an existing Holiday model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Holiday model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Holiday the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Holiday::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
