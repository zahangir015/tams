<?php

namespace app\modules\sale\controllers;

use app\modules\sale\models\HotelCategory;
use app\modules\sale\models\HotelProposal;
use app\modules\sale\models\RoomDetail;
use app\modules\sale\models\RoomType;
use app\modules\sale\models\search\HotelProposalSearch;
use app\controllers\ParentController;
use app\modules\sale\services\ProposalService;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * HotelProposalController implements the CRUD actions for HotelProposal model.
 */
class HotelProposalController extends ParentController
{

    public ProposalService $proposalService;

    public function __construct($uid, $module, $config = [])
    {
        $this->proposalService = new ProposalService();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all HotelProposal models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new HotelProposalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HotelProposal model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        $model = $this->proposalService->findHotelProposal($uid, ['roomDetails']);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new HotelProposal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new HotelProposal();

        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->proposalService->storeHotelProposal($requestData);
            if (!$response['error']) {
                Yii::$app->session->setFlash('success', $response['message']);
                return $this->redirect(['view', 'uid' => $response['model']->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $response['message']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'roomDetail' => new RoomDetail(),
            'categories' => ArrayHelper::map(HotelCategory::findAll(['status' => 1]), 'id', 'name'),
            'roomTypes' => ArrayHelper::map(RoomType::findAll(['status' => 1]), 'id', 'name')
        ]);
    }

    /**
     * Updates an existing HotelProposal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $hotelProposal = $this->proposalService->findHotelProposal($uid, ['roomDetails']);

        if ($this->request->isPost) {
            // Update Flight
            $updateResponse = $this->proposalService->updateHotelProposal(Yii::$app->request->post(), $hotelProposal);
            if ($updateResponse['error']) {
                Yii::$app->session->setFlash('danger', $updateResponse['message']);
            } else {
                Yii::$app->session->setFlash('success', $updateResponse['message']);
                return $this->redirect(['view', ['uid' => $hotelProposal->uid]]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HotelProposal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HotelProposal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return HotelProposal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HotelProposal::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionAddRoom($row): string
    {
        $model = new HotelProposal();
        $roomDetail = new RoomDetail();
        return $this->renderAjax('room', [
            'row' => $row,
            'model' => $model,
            'roomDetail' => $roomDetail,
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }
}
