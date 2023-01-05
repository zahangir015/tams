<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\modules\hrm\models\Branch;
use app\modules\hrm\models\Department;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\EmployeeDesignation;
use app\modules\hrm\models\EmployeeSearch;
use app\controllers\ParentController;
use app\modules\hrm\services\EmployeeService;
use app\modules\hrm\services\HrmConfigurationService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends ParentController
{
    public HrmConfigurationService $hrmConfigurationService;
    public EmployeeService $employeeService;

    public function __construct($id, $module, $config = [])
    {
        $this->hrmConfigurationService = new HrmConfigurationService();
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all Employee models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Employee();
        $designation = new EmployeeDesignation();
        $branches = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Branch::class, [], true);
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Department::class, [], true);

        if ($this->request->isPost) {
            // Store ticket data
            $storeResponse = $this->employeeService->storeEmployee(Yii::$app->request->post(), $model, $designation);
            if ($storeResponse) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
            $designation->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'designation' => $designation,
            'branchList' => ArrayHelper::map($branches, 'id', 'name'),
            'departmentList' => ArrayHelper::map($departments, 'id', 'name'),
        ]);
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $uid): Response
    {
        $this->findModel($uid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): Employee
    {
        if (($model = Employee::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    // THE CONTROLLER
    public function actionGetDesignationByDepartment()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $departmentId = $parents[0];
                $out = $this->hrmConfigurationService->getDesignationList(['departmentId' => $departmentId, 'status' => GlobalConstant::ACTIVE_STATUS]);
                // the getSubCatList function will query the database based on the
                // $departmentId and return an array like below:
                // [
                //    ['id'=>'<designation-id-1>', 'name'=>'<designation-name1>'],
                //    ['id'=>'<designation_id_2>', 'name'=>'<designation-name2>']
                // ]
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetEmployeeByDepartment()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $departmentId = $parents[0];
                $out = $this->hrmConfigurationService->getEmployeeList(['status' => GlobalConstant::ACTIVE_STATUS],['departmentId' => $departmentId, 'status' => GlobalConstant::ACTIVE_STATUS]);
                // the getSubCatList function will query the database based on the
                // $departmentId and return an array like below:
                // [
                //    ['id'=>'<designation-id-1>', 'name'=>'<designation-name1>'],
                //    ['id'=>'<designation_id_2>', 'name'=>'<designation-name2>']
                // ]
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }
}
