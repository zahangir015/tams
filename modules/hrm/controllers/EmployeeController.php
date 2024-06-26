<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\admin\models\form\Signup;
use app\modules\admin\models\User;
use app\modules\agent\models\Agency;
use app\modules\hrm\models\Branch;
use app\modules\hrm\models\Department;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\EmployeeDesignation;
use app\controllers\ParentController;
use app\modules\hrm\models\search\EmployeeSearch;
use app\modules\hrm\repositories\EmployeeRepository;
use app\modules\hrm\services\EmployeeService;
use app\modules\hrm\services\HrmConfigurationService;
use Exception;
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
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $agency = Agency::find()->with(['plan', 'users'])->where(['id' => Yii::$app->user->identity->agencyId])->asArray()->one();
        if (COUNT($agency['users']) >= $agency['plan']['userLimit']) {
            Yii::$app->session->setFlash('danger','You are out of your user limit.');
            return $this->redirect(['index']);
        }

        $signupModel = new Signup();
        $signupModel->agencyId = Yii::$app->user->identity->agencyId;
        $model = new Employee();
        $designation = new EmployeeDesignation();
        $branches = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS, 'agencyId' => Yii::$app->user->identity->agencyId], Branch::class, [], true);
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS, 'agencyId' => Yii::$app->user->identity->agencyId], Department::class, [], true);

        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            // Store ticket data
            $storeResponse = $this->employeeService->storeEmployee($requestData, $model, $designation, $signupModel, $agency);
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
        $branches = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS, 'agencyId' => Yii::$app->user->identity->agencyId], Branch::class, [], true);
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS, 'agencyId' => Yii::$app->user->identity->agencyId], Department::class, [], true);
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
        $model = $this->hrmConfigurationService->deleteModel(['uid' => $uid], Employee::class, []);
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger', 'Deletion failed - ' . Utilities::processErrorMessages($model->getErrors()));
        } else {
            Yii::$app->session->setFlash('success', 'Successfully Deleted.');
        }

        return $this->redirect(['index']);
    }

    public function findEmployee(string $uid, $withArray = []): ActiveRecord
    {
        return $this->employeeRepository->findOne(['uid' => $uid], Employee::class, $withArray);
    }

    public function actionGetEmployeeByDepartment(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $departmentId = $parents[0];
                $out = $this->hrmConfigurationService->getEmployeeList([Employee::tableName().'.status' => GlobalConstant::ACTIVE_STATUS], [EmployeeDesignation::tableName().'.departmentId' => $departmentId, EmployeeDesignation::tableName().'.status' => GlobalConstant::ACTIVE_STATUS]);
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

    public function actionGetEmployees($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $employees = $this->employeeRepository->employeeQuery($query);
        $data = [];
        foreach ($employees as $employee) {
            $data[] = ['id' => $employee->id, 'text' => $employee->officialId . ' |' . $employee->firstName . ' ' . $employee->lastName . ' | ' . $employee->officialEmail];
        }
        return ['results' => $data];
    }
}
