<?php

namespace app\modules\hrm\controllers;

use app\components\Utilities;
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
                $model = $this->payslipRepository->store($model);
                if ($model->hasErrors()) {
                    Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
                } else {
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
            } else {
                Yii::$app->session->setFlash('danger', 'Model loading failed.');
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
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->payslipService->findModel(['uid' => $uid], PayrollType::class);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model = $this->payslipRepository->store($model);
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
     * Deletes an existing PayrollType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->payslipService->deleteModel(['uid' => $uid], EmployeeShift::class, []);
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger', 'Deletion failed - ' . Utilities::processErrorMessages($model->getErrors()));
        } else {
            Yii::$app->session->setFlash('success', 'Successfully Deleted.');
        }

        return $this->redirect(['index']);
    }
}
