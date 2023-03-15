<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\LeaveAllocation;
use app\modules\hrm\models\EmployeeShift;
use app\modules\hrm\models\LeaveType;
use app\modules\hrm\models\search\LeaveAllocationSearch;
use app\controllers\ParentController;
use app\modules\hrm\repositories\HrmConfigurationRepository;
use app\modules\hrm\services\HrmConfigurationService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * EmployeeLeaveAllocationController implements the CRUD actions for LeaveAllocation model.
 */
class EmployeeLeaveAllocationController extends ParentController
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
     * Lists all LeaveAllocation models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new LeaveAllocationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'types' => $this->hrmConfigurationRepository->findAll(['status' => GlobalConstant::ACTIVE_STATUS], LeaveType::class, [], true, ['id', 'name'])
        ]);
    }

    /**
     * Displays a single LeaveAllocation model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->hrmConfigurationService->findModel(['uid' => $uid], LeaveAllocation::class, []),
        ]);
    }

    /**
     * Creates a new LeaveAllocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new LeaveAllocation();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
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
            'types' => ArrayHelper::map($this->hrmConfigurationRepository->findAll(['status' => GlobalConstant::ACTIVE_STATUS], LeaveType::class, [], true, ['id', 'name']), 'id', 'name')
        ]);
    }

    /**
     * Updates an existing LeaveAllocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->hrmConfigurationService->findModel(['uid' => $uid], LeaveAllocation::class, ['employee', 'leaveType']);

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
            'types' => ArrayHelper::map($this->hrmConfigurationRepository->findAll(['status' => GlobalConstant::ACTIVE_STATUS], LeaveType::class, [], true, ['id', 'name']), 'id', 'name')
        ]);
    }

    /**
     * Deletes an existing LeaveAllocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->hrmConfigurationService->deleteModel(['uid' => $uid], LeaveAllocation::class, []);
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger', 'Deletion failed - ' . Utilities::processErrorMessages($model->getErrors()));
        } else {
            Yii::$app->session->setFlash('success', 'Successfully Deleted.');
        }

        return $this->redirect(['index']);
    }

}
