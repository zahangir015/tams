<?php

namespace app\modules\hrm\controllers;

use app\modules\hrm\models\PublicHoliday;
use app\modules\hrm\models\search\PublicHolidaySearch;
use app\controllers\ParentController;
use Yii;
use yii\web\NotFoundHttpException;
use app\components\Helper;
use yii\web\Response;

/**
 * PublicHolidayController implements the CRUD actions for PublicHoliday model.
 */
class PublicHolidayController extends ParentController
{
    /**
     * Lists all PublicHoliday models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new PublicHolidaySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PublicHoliday model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->findModel($uid),
        ]);
    }

    /**
     * Creates a new PublicHoliday model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new PublicHoliday();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            } else {
                Yii::$app->session->setFlash('danger', Helper::processErrorMessages($model->getErrors()));
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PublicHoliday model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->findModel($uid);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            } else {
                Yii::$app->session->setFlash('danger', Helper::processErrorMessages($model->getErrors()));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PublicHoliday model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->findModel($uid);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        $model->save();
        Yii::$app->session->setFlash('success', 'Successfully Deleted');
        return $this->redirect(['index']);
    }

    /**
     * Finds the PublicHoliday model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return PublicHoliday the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): PublicHoliday
    {
        if (($model = PublicHoliday::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
