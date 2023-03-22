<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\EmployeePayroll;
use app\modules\hrm\models\EmployeePayrollTypeDetail;
use app\modules\hrm\models\PayrollType;
use app\modules\hrm\models\search\EmployeePayrollSearch;
use app\controllers\ParentController;
use app\modules\hrm\repositories\PayslipRepository;
use app\modules\hrm\services\PayslipService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * EmployeePayrollController implements the CRUD actions for EmployeePayroll model.
 */
class EmployeePayrollController extends ParentController
{
    private PayslipService $payslipService;
    private PayslipRepository $payslipRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->payslipService = new PayslipService();
        $this->payslipRepository = new PayslipRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all EmployeePayroll models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new EmployeePayrollSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmployeePayroll model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->payslipRepository->findOne(['uid' => $uid], EmployeePayroll::class, ['employee', 'employeePayrollTypeDetails' => function ($query) {
                return $query->with(['payrollType']);
            }]),
        ]);
    }

    /**
     * Creates a new EmployeePayroll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new EmployeePayroll();
        $payrolls = $this->payslipService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], PayrollType::class, [], true, ['id', 'name', 'category', 'amountType', 'calculatingMethod', 'amount']);
        if ($this->request->isPost) {
            $requestData = $this->request->post();
            // Store payroll data
            $employeePayrollStoreResponse = $this->payslipService->storeEmployeePayroll($model, $requestData);
            if (!$employeePayrollStoreResponse['error']) {
                return $this->redirect(['view', 'uid' => $employeePayrollStoreResponse['data']->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $employeePayrollStoreResponse['message']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'employeePayrollTypeDetail' => new EmployeePayrollTypeDetail(),
            'payrollList' => $payrolls,
        ]);
    }

    /**
     * Updates an existing EmployeePayroll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->payslipRepository->findOne(['uid' => $uid], EmployeePayroll::class, ['employee', 'employeePayrollTypeDetails' => function ($query) {
            return $query->with(['payrollType']);
        }]);
        $payrolls = $this->payslipService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], PayrollType::class, [], true, ['id', 'name', 'category', 'amountType', 'calculatingMethod', 'amount']);

        if ($this->request->isPost) {
            $requestData = $this->request->post();
            // Store payroll data
            $employeePayrollStoreResponse = $this->payslipService->updateEmployeePayroll($model, $requestData);
            if (!$employeePayrollStoreResponse['error']) {
                return $this->redirect(['view', 'uid' => $employeePayrollStoreResponse['data']->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $employeePayrollStoreResponse['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'employeePayrollTypeDetail' => new EmployeePayrollTypeDetail(),
            'payrollList' => $payrolls,
        ]);
    }

    /**
     * Deletes an existing EmployeePayroll model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EmployeePayroll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EmployeePayroll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmployeePayroll::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
