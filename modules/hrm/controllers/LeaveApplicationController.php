<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\LeaveApplication;
use app\modules\hrm\models\LeaveType;
use app\modules\hrm\models\search\LeaveApplicationSearch;
use app\controllers\ParentController;
use app\modules\hrm\repositories\AttendanceRepository;
use app\modules\hrm\services\AttendanceService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * LeaveApplicationController implements the CRUD actions for LeaveApplication model.
 */
class LeaveApplicationController extends ParentController
{
    private AttendanceService $attendanceService;
    private AttendanceRepository $attendanceRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->attendanceService = new AttendanceService();
        $this->attendanceRepository = new AttendanceRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all LeaveApplication models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new LeaveApplicationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LeaveApplication model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->attendanceService->findModel(['uid' => $uid], LeaveApplication::class, []),
        ]);
    }

    /**
     * Creates a new LeaveApplication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new LeaveApplication();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model = $this->attendanceRepository->store($model);
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
     * Updates an existing LeaveApplication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->attendanceService->findModel(['uid' => $uid], LeaveApplication::class, []);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model = $this->attendanceRepository->store($model);
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
     * Deletes an existing LeaveApplication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->attendanceService->deleteModel(['uid' => $uid], LeaveApplication::class, []);
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger', 'Deletion failed - ' . Utilities::processErrorMessages($model->getErrors()));
        } else {
            Yii::$app->session->setFlash('success', 'Successfully Deleted.');
        }

        return $this->redirect(['index']);
    }
}
