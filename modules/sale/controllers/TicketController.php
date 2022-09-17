<?php

namespace app\modules\sale\controllers;

use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketSearch;
use app\controllers\ParentController;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\services\FlightService;
use Yii;
use yii\bootstrap4\ActiveForm;
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
     * Displays a single Ticket model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid)
    {
        return $this->render('view', [
            'model' => $this->flightService->findTicket($uid, ['customer', 'ticketSupplier', 'airline', 'provider']),
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
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
    public function actionUpload()
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
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->flightService->findTicket($uid);

        if ($this->request->isPost) {

            // Update Ticket
            $model = $this->flightService->updateTicket(Yii::$app->request->post(), $model);
            if ($model) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
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
    public function actionDelete(string $uid)
    {
        $this->findModel($uid)->delete();

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
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }

    /*public function getData()
    {
        return [
            ['_csrf'] => 'dxluxf_vo-78LaLwZBiHr3v6Tmk3TF1s08Zqn_zJMBwkQyeglqrzuc9kk6hVW7TNHrR7IkZ1PAeakBr6tZ1BcA==',
            ['customerId'] => 1,
            ['invoice'] => 'on',
            ['group'] => 1,
            ['Ticket'] => [
                [0] => [
                    ['airlineId'] => 1,
                    ['commission'] => 0.07,
                    ['incentive'] => 0.003,
                    ['govTax'] => 0,
                    ['serviceCharge'] => 0,
                    ['type'] => 'new',
                    ['numberOfSegment'] => 1,
                    ['pnrCode'] => 'ASDDAF',
                    ['eTicket'] => '12356487',
                    ['paxName'] => 'Chloe Nolan',
                    ['paxType'] => 'A',
                    ['seatClass'] => 'Y',
                    ['providerId'] => 1,
                    ['route'] => 'DAC-CTG',
                    ['issueDate'] => '2022-09-05',
                    ['departureDate'] => '2022-09-10',
                    ['baseFare'] => 100,
                    ['tax'] => 10,
                    ['otherTax'] => 0,
                    ['quoteAmount'] => 150,
                    ['tripType'] => 'One Way',
                    ['bookedOnline'] => 0,
                    ['codeShare'] => 0,
                    ['baggage'] => '20kg',
                    ['reference'] => 'Ref',
                    ['customerId'] => 1,
                ],
                [2] => [
                    ['airlineId'] => 1,
                    ['commission'] => 0.07,
                    ['incentive'] => 0.003,
                    ['govTax'] => 0,
                    ['serviceCharge'] => 0,
                    ['type'] => 'new',
                    ['numberOfSegment'] => 1,
                    ['pnrCode'] => 'ASDDAF',
                    ['eTicket'] => '4535235345',
                    ['paxName'] => 'sdfgdsf sdfgsdg',
                    ['paxType'] => 'A',
                    ['seatClass'] => 'y',
                    ['providerId'] => 1,
                    ['route'] => 'DAC-ASD',
                    ['issueDate'] => '2022 - 09 - 05',
                    ['departureDate'] => '2022 - 09 - 10',
                    ['baseFare'] => 100,
                    ['tax'] => 10,
                    ['otherTax'] => 0,
                    ['quoteAmount'] => 150,
                    ['tripType'] => 'One Way',
                    ['bookedOnline'] => 0,
                    ['codeShare'] => 0,
                    ['baggage'] => '13kg',
                    ['reference'] => 'ref',
                    ['customerId'] => 1,
                ]
            ],
            ['TicketSupplier'] => [
                [0] => [
                    ['supplierId'] => 1,
                    ['status'] => 1,
                    ['paidAmount'] => 0,
                ],
                [2] => [
                    ['supplierId'] => 1,
                    ['status'] => 1,
                    ['paidAmount'] => 0,
                ]
            ]
        ];
    }*/
}
