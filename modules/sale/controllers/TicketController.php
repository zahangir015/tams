<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\models\History;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Airline;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\Provider;
use app\modules\sale\models\Supplier;
use app\modules\sale\models\ticket\RefundTicketSearch;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketSearch;
use app\controllers\ParentController;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\models\ticket\TicketRefund;
use app\modules\sale\models\ticket\TicketSupplierSearch;
use app\modules\sale\services\FlightService;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends ParentController
{

    public FlightService $flightService;

    public function __construct($uid, $module, $config = [])
    {
        $this->flightService = new FlightService();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Ticket models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all TicketSupplier models.
     *
     * @return string
     */
    public function actionTicketSupplierList()
    {
        $searchModel = new TicketSupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('ticket_supplier_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ticket model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTicketSupplier(string $uid): string
    {
        $model = $this->flightService->findTicket($uid, TicketSupplier::class, ['bill', 'supplier', 'ticket', 'airline']);
        return $this->render('ticket_supplier_view', [
            'model' => $model,
            'histories' => History::find()->where(['tableName' => Ticket::tableName(), 'tableId' => $model->id])->orderBy(['id' => SORT_DESC])->all()
        ]);
    }

    /**
     * Lists all Ticket models.
     *
     * @return string
     */
    public function actionRefundList(): string
    {
        $searchModel = new RefundTicketSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('refund_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ticket model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        $model = $this->flightService->findTicket($uid, Ticket::class, ['customer', 'ticketSupplier', 'airline', 'provider', 'ticketRefund']);
        return $this->render('view', [
            'model' => $model,
            'histories' => History::find()->where(['tableName' => Ticket::tableName(), 'tableId' => $model->id])->orderBy(['id' => SORT_DESC])->all()
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Ticket();
        $ticketSupplier = new TicketSupplier();
        if ($this->request->isPost) {
            // Store ticket data
            $storeResponse = $this->flightService->storeTicket(Yii::$app->request->post());
            if ($storeResponse) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'ticketSupplier' => $ticketSupplier,
        ]);
    }

    /**
     * Upload new Ticket models.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionUpload(): Response|string
    {
        $model = new Ticket();
        if (Yii::$app->request->isPost) {
            $requestData = Yii::$app->request->post();
            $csvFile = UploadedFile::getInstance($model, 'csv');
            if ($csvFile) {
                $uploadResponse = $this->flightService->uploadTicket($csvFile, $requestData);
                if ($uploadResponse) {
                    return $this->redirect(['index']);
                }
                /*$uploadResponse = $this->flightService->uploadTicket($csvFile, $requestData);
                if (!$ticketUploadResponse['error']) {
                    Yii::$app->session->setFlash('success', 'Ticket data uploaded successfully.');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', $ticketUploadResponse['message']);
                }*/
            }

            Yii::$app->session->setFlash('danger', 'File not found');
        }

        return $this->render('upload', [
            'model' => $model,
            'ticketSupplier' => new TicketSupplier(),
        ]);
    }

    /**
     * Creates a new Refund Type Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionRefund(string $uid): Response|string
    {
        $motherTicket = $this->flightService->findTicket($uid, Ticket::class, ['airline', 'provider', 'customer', 'ticketSupplier']);
        $totalReceivedAmount = $motherTicket->receivedAmount;
        if (($motherTicket->type == ServiceConstant::TYPE['Refund']) || ($motherTicket->type == ServiceConstant::TYPE['Refund Requested'])) {
            Yii::$app->session->setFlash('error', 'Refund and Refund Requested Ticket can not be refunded.');
            return $this->redirect(Yii::$app->request->referrer);
        } elseif ($motherTicket->type == ServiceConstant::TYPE['New']) {
            if (Ticket::findOne(['motherTicketId' => $motherTicket->id])) {
                Yii::$app->session->setFlash('error', 'This New ticket has a child ticket.');
                return $this->redirect(Yii::$app->request->referrer);
            }
            $totalReceivedAmount = $motherTicket->receivedAmount;
        } elseif ($motherTicket->type == ServiceConstant::TYPE['Reissue']) {
            if (!$motherTicket->motherTicketId) {
                Yii::$app->session->setFlash('error', 'Parent Ticket not found');
                return $this->redirect(Yii::$app->request->referrer);
            }
            if (Ticket::findOne(['motherTicketId' => $motherTicket->id])) {
                Yii::$app->session->setFlash('warning', 'This ticket has a parent ticket.');
                return $this->redirect(Yii::$app->request->referrer);
            }
            $totalReceivedAmount = $this->flightService->reissueParentChain($motherTicket->motherTicketId, $motherTicket->receivedAmount);
        }

        if ($this->request->isPost) {
            // Store refund ticket data
            $storeResponse = $this->flightService->addRefundTicket($motherTicket, Yii::$app->request->post());
            if ($storeResponse) {
                return $this->redirect(['refund-list']);
            }
        } else {
            $motherTicket->loadDefaultValues();
        }

        return $this->render('refund', [
            'model' => $motherTicket,
            'ticketSupplier' => new TicketSupplier(),
            'ticketRefund' => new TicketRefund(),
            'totalReceivedAmount' => $totalReceivedAmount,
        ]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->flightService->findTicket($uid, Ticket::class);

        if ($this->request->isPost) {
            // Update Ticket
            $response = $this->flightService->updateTicket(Yii::$app->request->post(), $model);
            if (!$response['error']) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $response['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionRefundUpdate(string $uid): Response|string
    {
        $model = $this->flightService->findTicket($uid, Ticket::class, ['customer', 'ticketSupplier', 'airline', 'provider', 'ticketRefund']);

        if ($this->request->isPost) {
            // Update Ticket
            $response = $this->flightService->updateRefundTicket(Yii::$app->request->post(), $model);
            if (!$response['error']) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $response['message']);
            }
        }

        return $this->render('refund_update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->flightService->findTicket($uid, Ticket::class, ['ticketSupplier']);

        if ($this->request->isPost) {
            // Delete Ticket
            $response = $this->flightService->deleteTicket($model);
            if (!$response['error']) {
                Yii::$app->session->setFlash('success', $response['message']);
            } else {
                Yii::$app->session->setFlash('danger', $response['message']);
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        }

        return $this->redirect(['index']);
    }

    public function actionGetMotherTicket($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $tickets = Ticket::query($query);
        $data = [];
        foreach ($tickets as $ticket) {
            $data[] = ['id' => $ticket->id, 'text' => $ticket->eTicket . ' (' . $ticket->pnrCode . ')'];
        }
        return ['results' => $data];
    }

    public function actionAddTicket($row): string
    {
        $model = new Ticket();
        $ticketSupplier = new TicketSupplier();
        return $this->renderAjax('ticket', [
            'model' => $model,
            'row' => $row,
            'ticketSupplier' => $ticketSupplier,
            'supplierDataArray' => Supplier::query(),
            'providerDataArray' => Provider::query(),
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }

    public function actionAjaxCostCalculate($baseFare, $tax, $airlineId)
    {
        return $this->flightService->ajaxCostCalculation($baseFare, $tax, $airlineId);
    }

    public function actionGetParentTicketDetails(int $motherTicketId): array|ActiveRecord|null
    {
        return Ticket::find()
            ->select(['id', 'paxName', 'paxType', 'route', 'flightType', 'tripType'])
            ->where(['id' => $motherTicketId])
            ->andWhere([Ticket::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([Ticket::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
            ->one();
    }

    public function actionUpdateFlightStatus()
    {
        if (isset($_POST['hasEditable'])) {
            $model = Ticket::findOne(['id' => $_POST['editableKey']]); // your model can be loaded here
            $model->flightStatus = $_POST['Ticket'][$_POST['editableIndex']]['flightStatus'];
            if (!$model->save()) {
                return json_encode($model->getErrors());
            }
            return json_encode($_POST);
        }

        return json_encode($_POST);
    }
}
