<?php

namespace app\modules\serviceInventory\controllers;

use Yii;
use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\serviceInventory\models\HotelInventory;
use app\modules\serviceInventory\models\HotelInventorySearch;
use app\modules\serviceInventory\models\HotelInventoryRoomDetail;
use app\modules\serviceInventory\models\HotelInventoryRoomDetailSearch;
use app\modules\serviceInventory\models\HotelInventoryAmenity;
use app\modules\serviceInventory\models\HotelInventoryAmenitySearch;
use app\modules\serviceInventory\models\Amenity;
use app\modules\serviceInventory\models\AmenitySearch;
use app\controllers\ParentController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\sale\models\hotel\HotelCategory;
use app\modules\sale\models\RoomDetail;
use app\modules\sale\models\RoomType;
use app\modules\sale\services\ProposalService;

/**
 * HotelInventoryController implements the CRUD actions for HotelInventory model.
 */
class HotelInventoryController extends ParentController
{
    // /**
    //  * @inheritDoc
    //  */
    // public function behaviors()
    // {
    //     return array_merge(
    //         parent::behaviors(),
    //         [
    //             'verbs' => [
    //                 'class' => VerbFilter::className(),
    //                 'actions' => [
    //                     'delete' => ['POST'],
    //                 ],
    //             ],
    //         ]
    //     );
    // }

    public ProposalService $proposalService;

    public function __construct($uid, $module, $config = [])
    {
        $this->proposalService = new ProposalService();
        parent::__construct($uid,$module,$config);
    }

    /**
     * Lists all HotelInventory models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new HotelInventorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $searchModel2 = new HotelInventoryRoomDetailSearch();
        $dataProvider2 = $searchModel2->search($this->request->queryParams);
        $searchModel3 = new HotelInventoryAmenitySearch();
        $dataProvider3 = $searchModel3->search($this->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,
            'searchModel3' => $searchModel3,
            'dataProvider3' => $dataProvider3
        ]);
    }

    /**
     * Displays a single HotelInventory model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid): string
    {
        $model = $this->proposalService->findHotelProposal($uid,['roomDetails']);

        return $this->render('view', [
            'model' => $this->findModel($uid),
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
     * @param string $uid UID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        //$model = $this->findModel($uid);
        $model = $this->proposalService->findHotelProposal($uid, ['roomDetails']);

        if ($this->request->isPost){
            $updateResponse = $this->proposalService->updateHotelProposal(Yii::$app-request->post(),$hotelInventory);
            if(!$updateResponse['error'])
            {
                Yii::$app->session->setFlash('success',$updateResponse['message']);
                return $this->redirect(['view',['uid' => $model->uid]]);
            }
            else
            {
                Yii::$app->session->setFlash('danger',$updateResponse['message']);
            }
            // if($model->load($this->request->post()) && $model->save())
            // {
            //     Yii::$app->session->setFlash('success','HotelInventory updated successfully');
            //     return $this->redirect(['view', 'uid' => $model->uid]);
            // }
        }

        return $this->render('update', [
            'model' => $model,
            'roomDetail' => new RoomDetail(),
            'categories' => ArrayHelper::map(HotelCategory::findAll(['status' => 1]),'id','name'),
            'roomTypes' => ArrayHelper::map(RoomType::findAll(['status' => 1]),'id','name')
        ]);
    }

    /**
     * Deletes an existing HotelInventory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $id): Response
    {
        $this->findModel($id)->delete();
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the HotelInventory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return HotelInventory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): HotelInventory
    {
        if (($model = HotelInventory::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionAddRoom($row): string
    {
        $model = new HotelInventory();
        $roomDetail = new RoomDetail();

        return $this->renderAjax('room',[
            'row' => $row,
            'model' => $model,
            'roomDetail' => $roomDetail,
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }
}
