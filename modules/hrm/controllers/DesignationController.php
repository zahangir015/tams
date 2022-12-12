<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\modules\hrm\models\Department;
use app\modules\hrm\models\Designation;
use app\modules\hrm\models\DesignationSearch;
use app\controllers\ParentController;
use app\modules\hrm\services\HrmConfigurationService;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DesignationController implements the CRUD actions for Designation model.
 */
class DesignationController extends ParentController
{
    public HrmConfigurationService $hrmConfigurationService;

    public function __construct($uid, $module, $config = [])
    {
        $this->hrmConfigurationService = new HrmConfigurationService();
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
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Department::class, [], true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'departments' => ArrayHelper::map($departments, 'id', 'name'),
        ]);
    }

    /**
     * Displays a single Designation model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid)
    {
        return $this->render('view', [
            'model' => $this->hrmConfigurationService->findModel(['status' => GlobalConstant::ACTIVE_STATUS], Designation::class, ['department']),
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
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'uid' => $model->uid]);
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
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->hrmConfigurationService->findModel(['uid' => $uid], Designation::class, ['department']);
        $departments = $this->hrmConfigurationService->getAll(['status' => GlobalConstant::ACTIVE_STATUS], Department::class, [], true);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'uid' => $model->uid]);
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
}
