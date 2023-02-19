<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\Shift;
use app\modules\hrm\models\search\ShiftSearch;
use app\controllers\ParentController;
use app\modules\hrm\repositories\HrmConfigurationRepository;
use app\modules\hrm\services\HrmConfigurationService;
use DateTime;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ShiftController implements the CRUD actions for Shift model.
 */
class ShiftController extends ParentController
{
    public HrmConfigurationService $hrmConfigurationService;
    public HrmConfigurationRepository $hrmConfigurationRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->hrmConfigurationService = new HrmConfigurationService();
        $this->hrmConfigurationRepository = new HrmConfigurationRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Weekend models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new ShiftSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Weekend model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->hrmConfigurationService->findModel(['uid' => $uid], Shift::class, []),
        ]);
    }

    /**
     * Creates a new Shift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Shift();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $duration = abs(strtotime($model->exitTime) - strtotime($model->entryTime)) / (60 * 60);
                $hours = (int)($duration / 60);
                $minutes = $duration - ($hours * 60);
                $date = new DateTime($hours . ":" . $minutes);
                $model->totalHours = $date->format('H:i:s');

                $model = $this->hrmConfigurationRepository->store($model);
                if ($model->hasErrors()) {
                    Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
                } else {
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Shift model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->hrmConfigurationService->findModel(['uid' => $uid], Shift::class, []);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $duration = abs(strtotime($model->exitTime) - strtotime($model->entryTime)) / (60 * 60);
                $hours = (int)($duration / 60);
                $minutes = $duration - ($hours * 60);
                $date = new DateTime($hours . ":" . $minutes);
                $model->totalHours = $date->format('H:i:s');
                $model = $this->hrmConfigurationRepository->store($model);
                if ($model->hasErrors()) {
                    Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
                } else {
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Shift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->hrmConfigurationService->deleteModel(['uid' => $uid], Shift::class, []);
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger', 'Deletion failed - ' . Utilities::processErrorMessages($model->getErrors()));
        } else {
            Yii::$app->session->setFlash('success', 'Successfully Deleted.');
        }

        return $this->redirect(['index']);
    }

    public function actionGetShifts($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $shifts = Shift::query($query);
        $data = [];
        foreach ($shifts as $shift) {
            $data[] = ['id' => $shift->id, 'text' => $shift->title];
        }
        return ['results' => $data];
    }
}
