<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\components\HrmConstant;
use app\modules\hrm\models\LeaveApplication;
use app\modules\hrm\models\LeaveApprovalHistory;
use app\modules\hrm\models\LeaveType;
use app\modules\hrm\models\search\LeaveApplicationSearch;
use app\controllers\ParentController;
use app\modules\hrm\models\search\LeaveApprovalHistorySearch;
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
     * Lists all LeaveApplication models.
     *
     * @return string
     */
    public function actionAppliedLeaves(): string
    {
        $searchModel = new LeaveApplicationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all LeaveApprovalHistory models.
     *
     * @return string
     */
    public function actionApprovalHistory(): string
    {
        $searchModel = new LeaveApprovalHistorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('approval_list', [
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
            'model' => $this->attendanceService->findModel(['uid' => $uid], LeaveApplication::class, ['leaveApprovalHistories', 'employee', 'leaveType']),
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
            $requestData = Yii::$app->request->post();

            // Check Validity
            $validityCheckResponse = $this->attendanceService->applicationValidityCheck($requestData['LeaveApplication']);
            if (!$validityCheckResponse['error']) {
                $leaveStoringResponse = $this->attendanceService->storeLeave($model, Yii::$app->request->post(), $validityCheckResponse['data']);
                if ($leaveStoringResponse['error']) {
                    Yii::$app->session->setFlash('danger', $leaveStoringResponse['message']);
                } else {
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
            } else {
                Yii::$app->session->setFlash('danger', $validityCheckResponse['message']);
            }

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionApply(): Response|string
    {
        $model = new LeaveApplication();

        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $requestData['LeaveApplication']['employeeId'] = Yii::$app->user->id;
            // Check Validity
            $validityCheckResponse = $this->attendanceService->applicationValidityCheck($requestData['LeaveApplication']);
            if (!$validityCheckResponse['error']) {
                $leaveStoringResponse = $this->attendanceService->storeLeave($model, $requestData, $validityCheckResponse['data']);
                if ($leaveStoringResponse['error']) {
                    Yii::$app->session->setFlash('danger', $leaveStoringResponse['message']);
                } else {
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
            } else {
                Yii::$app->session->setFlash('danger', $validityCheckResponse['message']);
            }

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('apply', [
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
        $model = $this->attendanceService->findModel(['uid' => $uid], LeaveApplication::class, ['employee', 'leaveType', 'leaveApprovalHistories']);
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            if (!isset($requestData['LeaveApplication']['employeeId'])) {
                $requestData['LeaveApplication']['employeeId'] = Yii::$app->user->id;
            }
            // Check Validity
            $validityCheckResponse = $this->attendanceService->applicationValidityCheck($requestData['LeaveApplication']);
            if (!$validityCheckResponse['error']) {
                $leaveStoringResponse = $this->attendanceService->updateLeave($model, $requestData, $validityCheckResponse['data']);
                if ($leaveStoringResponse['error']) {
                    Yii::$app->session->setFlash('danger', $leaveStoringResponse['message']);
                } else {
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
            } else {
                Yii::$app->session->setFlash('danger', $validityCheckResponse['message']);
            }
        }
        // Validation check before update
        if (array_search(HrmConstant::APPROVAL_STATUS['Approved'], ArrayHelper::toArray($model->leaveApprovalHistories)) !== false) {
            Yii::$app->session->setFlash('danger', 'This application is already approved. This application is updatable after cancellation.');
            $this->redirect('index');
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

    /**
     * Deletes an existing LeaveApplication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionApprove(string $uid): Response
    {
        $model = $this->attendanceRepository->findOne(['uid' => $uid], LeaveApprovalHistory::class, ['requested']);

        if ($model->requested->userId !== Yii::$app->user->id) {
            Yii::$app->session->setFlash('danger', 'Invalid request. You are not allowed to perform this.');
        } else {
            $model->approvalStatus = HrmConstant::APPROVAL_STATUS['Approved'];
            $model = $this->attendanceRepository->store($model);
            if ($model->hasErrors()) {
                Yii::$app->session->setFlash('danger', 'Approval failed - ' . Utilities::processErrorMessages($model->getErrors()));
            } else {
                Yii::$app->session->setFlash('success', 'Successfully Deleted.');
            }
        }

        return $this->redirect(['approval-history']);
    }

    /**
     * Cancel an existing LeaveApprovalHistory model.
     * If cancel is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionCancel(string $uid): Response
    {
        $model = $this->attendanceRepository->findOne(['uid' => $uid], LeaveApprovalHistory::class, ['requested']);

        if ($model->requested->userId !== Yii::$app->user->id) {
            Yii::$app->session->setFlash('danger', 'Invalid request. You are not allowed to perform this.');
        } else {
            $model->approvalStatus = HrmConstant::APPROVAL_STATUS['Approved'];
            $model = $this->attendanceRepository->store($model);
            if ($model->hasErrors()) {
                Yii::$app->session->setFlash('danger', 'Approval failed - ' . Utilities::processErrorMessages($model->getErrors()));
            } else {
                Yii::$app->session->setFlash('success', 'Successfully Deleted.');
            }
        }
        return $this->redirect(['approval-history']);
    }
}
