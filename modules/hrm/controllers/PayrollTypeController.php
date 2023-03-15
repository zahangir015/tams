<?php

namespace app\modules\hrm\controllers;

use app\modules\hrm\models\PayrollType;
use app\modules\hrm\models\search\PayrollTypeSearch;
use app\controllers\ParentController;
use app\modules\hrm\repositories\PayslipRepository;
use app\modules\hrm\services\PayslipService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PayrollTypeController implements the CRUD actions for PayrollType model.
 */
class PayrollTypeController extends ParentController
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
     * Lists all PayrollType models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new PayrollTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PayrollType model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->payslipRepository->findOne(['uid' => $uid], PayrollType::class),
        ]);
    }

    /**
     * Creates a new PayrollType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new PayrollType();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $payrollTypeStoreResponse = $this->payslipService->storePayrollType($model);
                if ($payrollTypeStoreResponse['error']) {
                    Yii::$app->session->setFlash('danger', $payrollTypeStoreResponse['message']);
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
     * Updates an existing PayrollType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PayrollType model.
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
     * Finds the PayrollType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PayrollType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PayrollType::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
