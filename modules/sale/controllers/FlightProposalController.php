<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\modules\sale\models\FlightProposal;
use app\modules\sale\models\FlightProposalItinerary;
use app\modules\sale\models\search\FlightProposalSearch;
use app\controllers\ParentController;
use app\modules\sale\services\ProposalService;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * FlightProposalController implements the CRUD actions for FlightProposal model.
 */
class FlightProposalController extends ParentController
{
    public ProposalService $proposalService;

    public function __construct($uid, $module, $config = [])
    {
        $this->proposalService = new ProposalService();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all FlightProposal models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new FlightProposalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hotel model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        $model = $this->proposalService->findFlightProposal($uid, ['flightProposalItineraries']);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Hotel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new FlightProposal();
        $itinerary = new FlightProposalItinerary();
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $response = $this->proposalService->storeFlightProposal($requestData);
            if ($response) {
                return $this->redirect('index');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'itinerary' => $itinerary,
        ]);
    }

    /**
     * Updates an existing Hotel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->proposalService->findFlightProposal($uid, ['flightProposalItineraries']);

        if ($this->request->isPost) {
            // Update Flight
            $updateResponse = $this->proposalService->updateFlightProposal(Yii::$app->request->post(), $model);
            if ($updateResponse['error']) {
                Yii::$app->session->setFlash('danger', $updateResponse['message']);
            } else {
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
    public function actionDelete(string $uid): Response
    {
        $model = $this->proposalService->findFlightProposal($uid);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionAddItinerary($row): string
    {
        $model = new FlightProposal();
        $itinerary = new FlightProposalItinerary();
        return $this->renderAjax('itinerary', [
            'row' => $row,
            'model' => $model,
            'itinerary' => $itinerary,
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }
}
