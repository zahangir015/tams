<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\modules\admin\models\form\Signup;
use app\modules\hrm\models\Branch;
use app\modules\hrm\models\Department;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\EmployeeDesignation;
use app\controllers\ParentController;
use app\modules\hrm\models\search\EmployeeSearch;
use app\modules\hrm\repositories\EmployeeRepository;
use app\modules\hrm\services\EmployeeService;
use app\modules\hrm\services\HrmConfigurationService;
use Yii;
use yii\db\ActiveRecord;
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
    public EmployeeRepository $employeeRepository;

    public function __construct($id, $module, $config = [])
    {
        $this->hrmConfigurationService = new HrmConfigurationService();
        $this->employeeService = new EmployeeService();
        $this->employeeRepository = new EmployeeRepository();
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
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->employeeRepository->findOne(['uid' => $uid], Employee::class, ['employeeDesignation']),
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $signupModel = new Signup();
        $model = new Employee();
        $designation = new EmployeeDesignation();
        $branches = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Branch::class, [], true);
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Department::class, [], true);

        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            // Store ticket data
            $storeResponse = $this->employeeService->storeEmployee($requestData, $model, $designation, $signupModel);
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
            'signup' => $signupModel
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
        $model = $this->employeeRepository->findOne(['uid' => $uid], Employee::class, ['employeeDesignation']);
        $designation = $model->employeeDesignation;
        $branches = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Branch::class, [], true);
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Department::class, [], true);
        // Update Employee Data
        if ($this->request->isPost) {
            // Update Employee
            $updateStatus = $this->employeeService->updateEmployee(Yii::$app->request->post(), $model, $designation);
            if ($updateStatus) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'designation' => $designation,
            'branchList' => ArrayHelper::map($branches, 'id', 'name'),
            'departmentList' => ArrayHelper::map($departments, 'id', 'name'),
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

    public function findEmployee(string $uid, $withArray = []): ActiveRecord
    {
        return $this->employeeRepository->findOne(['uid' => $uid], Employee::class, $withArray);
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
                $out = $this->hrmConfigurationService->getEmployeeList(['status' => GlobalConstant::ACTIVE_STATUS], ['departmentId' => $departmentId, 'status' => GlobalConstant::ACTIVE_STATUS]);
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
