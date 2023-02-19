<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\DepartmentShift;
use app\modules\hrm\models\search\DepartmentShiftSearch;
use app\controllers\ParentController;
use app\modules\hrm\models\Weekend;
use app\modules\hrm\repositories\HrmConfigurationRepository;
use app\modules\hrm\services\HrmConfigurationService;
use Yii;
use yii\web\Response;

/**
 * DepartmentShiftController implements the CRUD actions for DepartmentShift model.
 */
class DepartmentShiftController extends ParentController
{
    public HrmConfigurationService $hrmConfigurationService;
    public HrmConfigurationRepository $hrmConfigurationRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->hrmConfigurationService = new HrmConfigurationService();
        $this->hrmConfigurationRepository = new HrmConfigurationRepository();
        parent::__construct($uid, $module, $config);
    }
    /**
     * Lists all DepartmentShift models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new DepartmentShiftSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DepartmentShift model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->hrmConfigurationService->findModel(['uid' => $uid], DepartmentShift::class, ['department', 'shift']),
        ]);
    }

    /**
     * Creates a new DepartmentShift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new DepartmentShift();

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
        ]);
    }

    /**
     * Updates an existing DepartmentShift model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->hrmConfigurationService->findModel(['uid' => $uid], DepartmentShift::class, ['department', 'shift']);

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
        ]);
    }

    /**
     * Deletes an existing DepartmentShift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->hrmConfigurationService->deleteModel(['uid' => $uid], DepartmentShift::class, []);
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger', 'Deletion failed - ' . Utilities::processErrorMessages($model->getErrors()));
        } else {
            Yii::$app->session->setFlash('success', 'Successfully Deleted.');
        }

        return $this->redirect(['index']);
    }

    public function actionGetShiftByDepartment(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $departmentId = $parents[0];
                $out = $this->hrmConfigurationService->getShiftListByDepartment(['departmentId' => $departmentId, 'status' => GlobalConstant::ACTIVE_STATUS]);
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
