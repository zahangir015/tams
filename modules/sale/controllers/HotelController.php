<?php

namespace app\modules\sale\controllers;

use app\models\History;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\hotel\HotelSearch;
use app\controllers\ParentController;
use app\modules\sale\models\hotel\HotelSupplier;
use app\modules\sale\services\HotelService;
use app\modules\sales\models\hotel\HotelSupplierSearch;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * HotelController implements the CRUD actions for Hotel model.
 */
class HotelController extends ParentController
{
    public HotelService $hotelService;

    public function __construct($uid, $module, $config = [])
    {
        $this->hotelService = new HotelService();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Hotel models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HotelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all HotelSupplier models.
     *
     * @return string
     */
    public function actionHotelSupplierList()
    {
        $searchModel = new HotelSupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('hotel_supplier_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hotel model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid)
    {
        $model = $this->hotelService->findHotel($uid, ['hotelSuppliers', 'customer']);
        return $this->render('view', [
            'model' => $model,
            'histories' => History::find()->where(['tableName' => Hotel::tableName(), 'tableId' => $model->id])->orderBy(['id' => SORT_DESC])->all()
        ]);
    }

    /**
     * Creates a new Hotel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Hotel();
        $hotelSupplier = new HotelSupplier();
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->hotelService->storeHotel($requestData);
            if ($response) {
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'hotelSupplier' => $hotelSupplier,
        ]);
    }

    /**
     * Creates a new Hotel model.
     * If creation is successful, the browser will be redirected to the 'refund list' page.
     * @return string|Response
     */
    public function actionRefund(string $uid)
    {
        $model = new Hotel();
        $motherHotel = $this->hotelService->findHotel($uid, ['hotelSuppliers', 'invoice', 'customer']);
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->hotelService->refundHotel($requestData, $motherHotel);
            if ($response) {
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_refund', [
            'model' => $model,
            'motherHotel' => $motherHotel,
        ]);
    }

    /**
     * Updates an existing Hotel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->hotelService->findHotel($uid, ['hotelSuppliers', 'customer']);

        if ($this->request->isPost) {
            // Update Hotel
            $model = $this->hotelService->updateHotel(Yii::$app->request->post(), $model);
            if ($model) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Hotel model.
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

    public function actionAddSupplier($row): string
    {
        $model = new Hotel();
        $hotelSupplier = new HotelSupplier();
        return $this->renderAjax('supplier', [
            'row' => $row,
            'model' => $model,
            'hotelSupplier' => $hotelSupplier,
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }
}
