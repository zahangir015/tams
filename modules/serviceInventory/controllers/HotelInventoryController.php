<?php

namespace app\modules\serviceInventory\controllers;

use app\modules\serviceInventory\models\HotelInventory;
use app\modules\serviceInventory\models\HotelInventorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HotelInventoryController implements the CRUD actions for HotelInventory model.
 */
class HotelInventoryController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all HotelInventory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HotelInventorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HotelInventory model.
     * @param int $id ID
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
     * Creates a new HotelInventory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new HotelInventory();
        $model2 = new HotelInventoryRoomDetail();
        $model3 = new HotelInventoryAmenity();
        $model4 = new Amenity();
                
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->proposalService->storeHotelProposal($requestData);
            if(!$response['error'])
            {
                Yii::$app->session->setFlash('success',$response['message']);
                return $this->redirect(['view', 'uid' => $response['model']->uid]);
            }
            else
            {
                Yii::$app->session->setFlash('danger',$response['message']);
            }
            // if ($model->load($this->request->post()) && $model->save()) {
            //     Yii::$app->session->setFlash('success','HotelInventory created successfully');
            //     return $this->redirect(['view', 'uid' => $model->uid]);
            // }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'model2' => $model2,
            'model3' => $model3,
            'model4' => $model4,
            'roomDetail' => new RoomDetail(),
            'categories' => ArrayHelper::map(HotelCategory::findAll(['status' => 1 ]),'id','name'),
            'roomTypes' => ArrayHelper::map(RoomType::findAll(['status' => 1]),'id','name')
        ]);
    }

    /**
     * Updates an existing HotelInventory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
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
     * Deletes an existing HotelInventory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HotelInventory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return HotelInventory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HotelInventory::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
