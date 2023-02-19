<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\LeaveType;
use app\modules\hrm\models\YearlyLeaveAllocation;
use app\modules\hrm\models\search\YearlyLeaveAllocationSearch;
use app\controllers\ParentController;
use app\modules\hrm\repositories\HrmConfigurationRepository;
use app\modules\hrm\services\HrmConfigurationService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * YearlyLeaveAllocationController implements the CRUD actions for YearlyLeaveAllocation model.
 */
class YearlyLeaveAllocationController extends ParentController
{
    private HrmConfigurationService $hrmConfigurationService;
    private HrmConfigurationRepository $hrmConfigurationRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->hrmConfigurationService = new HrmConfigurationService();
        $this->hrmConfigurationRepository = new HrmConfigurationRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all YearlyLeaveAllocation models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new YearlyLeaveAllocationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single YearlyLeaveAllocation model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->hrmConfigurationService->findModel(['uid' => $uid], YearlyLeaveAllocation::class, []),
        ]);
    }

    /**
     * Creates a new YearlyLeaveAllocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new YearlyLeaveAllocation();
        if ($this->request->isPost) {
            $requestData = $this->request->post();
            $allocationInsertResponse = $this->hrmConfigurationService->batchInsertYearlyAllocation($requestData);
            if (!$allocationInsertResponse['error']) {
                Yii::$app->session->setFlash('success', $allocationInsertResponse['message']);
                return $this->redirect(['index']);
            }

            Yii::$app->session->setFlash('danger', $allocationInsertResponse['message']);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'types' => $this->hrmConfigurationRepository->findAll(['status' => GlobalConstant::ACTIVE_STATUS], LeaveType::class, [], true)
        ]);
    }

    /**
     * Updates an existing YearlyLeaveAllocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->hrmConfigurationService->findModel(['uid' => $uid], YearlyLeaveAllocation::class, []);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
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
     * Deletes an existing YearlyLeaveAllocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->hrmConfigurationService->deleteModel(['uid' => $uid], YearlyLeaveAllocation::class, []);
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger', 'Deletion failed - ' . Utilities::processErrorMessages($model->getErrors()));
        } else {
            Yii::$app->session->setFlash('success', 'Successfully Deleted.');
        }

        return $this->redirect(['index']);
    }
}
