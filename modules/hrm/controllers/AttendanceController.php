<?php

namespace app\modules\hrm\controllers;

use app\components\Utilities;
use app\modules\hrm\models\Attendance;
use app\modules\hrm\models\search\AttendanceSearch;
use app\controllers\ParentController;
use app\modules\hrm\models\search\IndividualAttendanceSearch;
use app\modules\hrm\repositories\AttendanceRepository;
use app\modules\hrm\services\AttendanceService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AttendanceController implements the CRUD actions for Attendance model.
 */
class AttendanceController extends ParentController
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
     * Lists all Attendance models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new AttendanceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Attendance models.
     *
     * @return string
     */
    public function actionAttendanceList(): string
    {
        $employee = Yii::$app->user->identity->employee;

        if (!$employee) {
            Yii::$app->session->setFlash('warning', 'Employee Profile is required.');
            $currentDaysAttendance = null;
        } else {
            $currentDaysAttendance = $this->attendanceService->findModel(['employeeId' => $employee->id, 'date' => date('Y-m-d')], Attendance::class);
        }

        $searchModel = new IndividualAttendanceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('attendance_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'employee' => $employee,
            'currentDaysAttendance' => $currentDaysAttendance,
        ]);
    }

    /**
     * Displays a single Attendance model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->attendanceService->findModel(['uid' => $uid], Attendance::class, []),
        ]);
    }

    /**
     * Creates a new Attendance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Attendance();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $attendanceStoreResponse = $this->attendanceService->storeAttendance($model);
                if ($attendanceStoreResponse['error']) {
                    Yii::$app->session->setFlash('danger', $attendanceStoreResponse['message']);
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
     * Creates a new Attendance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionEntry(): Response|string
    {
        if ($this->request->isPost) {
            $model = new Attendance();
            $attendanceStoreResponse = $this->attendanceService->attendanceEntry($model);
            if ($attendanceStoreResponse['error']) {
                Yii::$app->session->setFlash('danger', $attendanceStoreResponse['message']);
            } else {
                Yii::$app->session->setFlash('success', 'Entry successfully done.');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Invalid Request.');
        }


        return $this->redirect('index');
    }

    /**
     * Creates a new Attendance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionExit(): Response|string
    {
        if ($this->request->isPost) {
            $model = new Attendance();
            $attendanceStoreResponse = $this->attendanceService->attendanceExit($model);
            if ($attendanceStoreResponse['error']) {
                Yii::$app->session->setFlash('danger', $attendanceStoreResponse['message']);
            } else {
                Yii::$app->session->setFlash('success', 'Entry successfully done.');
            }
        } else {
            Yii::$app->session->setFlash('danger', 'Invalid Request.');
        }


        return $this->redirect('index');
    }

    /**
     * Updates an existing Attendance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->attendanceService->findModel(['uid' => $uid], Attendance::class, []);

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
     * Deletes an existing Attendance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->attendanceService->deleteModel(['uid' => $uid], Attendance::class, []);
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger', 'Deletion failed - ' . Utilities::processErrorMessages($model->getErrors()));
        } else {
            Yii::$app->session->setFlash('success', 'Successfully Deleted.');
        }

        return $this->redirect(['index']);
    }
}
