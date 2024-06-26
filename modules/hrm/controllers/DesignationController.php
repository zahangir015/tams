<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\hrm\models\Department;
use app\modules\hrm\models\Designation;
use app\controllers\ParentController;
use app\modules\hrm\models\search\DesignationSearch;
use app\modules\hrm\repositories\HrmConfigurationRepository;
use app\modules\hrm\services\HrmConfigurationService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DesignationController implements the CRUD actions for Designation model.
 */
class DesignationController extends ParentController
{
    private HrmConfigurationService $hrmConfigurationService;
    private HrmConfigurationRepository $hrmConfigurationRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->hrmConfigurationService = new HrmConfigurationService();
        $this->hrmConfigurationRepository = new HrmConfigurationRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Designation models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new DesignationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Designation model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->hrmConfigurationService->findModel(['uid' => $uid], Designation::class, ['department']),
        ]);
    }

    /**
     * Creates a new Designation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Designation();
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Department::class, [], true);

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
            'departments' => ArrayHelper::map($departments, 'id', 'name')
        ]);
    }

    /**
     * Updates an existing Designation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->hrmConfigurationService->findModel(['uid' => $uid], Designation::class, ['department']);
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Department::class, [], true);

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
            'departments' => ArrayHelper::map($departments, 'id', 'name')
        ]);
    }

    /**
     * Deletes an existing Designation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($uid)
    {
        $this->findModel($uid)->delete();

        return $this->redirect(['index']);
    }

    public function actionGetDesignationByDepartment(): array
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
}
