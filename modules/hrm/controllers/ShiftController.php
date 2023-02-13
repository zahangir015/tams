<?php

namespace app\modules\hrm\controllers;

use app\components\GlobalConstant;
use app\components\Helper;
use app\modules\hrm\models\Shift;
use app\modules\hrm\models\search\ShiftSearch;
use app\controllers\ParentController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ShiftController implements the CRUD actions for Shift model.
 */
class ShiftController extends ParentController
{
    /**
     * Lists all Shift models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new ShiftSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Shift model.
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
     * Creates a new Shift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Shift();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                dd(abs(strtotime($model->exitTime) - strtotime($model->entryTime))/(60*60));
                //$model->totalHours =
                if ($model->save()) {
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
            }
            Yii::$app->session->setFlash('danger', Helper::processErrorMessages($model->getErrors()));
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Shift model.
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
            }
            Yii::$app->session->setFlash('danger', Helper::processErrorMessages($model->getErrors()));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Shift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $uid)
    {
        $model = $this->findModel($uid);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        $model->save();
        Yii::$app->session->setFlash('success', 'Successfully Deleted');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Shift model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Shift the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): Shift
    {
        if (($model = Shift::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionSearch($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $shifts = Shift::query($query);
        $data = [];
        foreach ($shifts as $shift) {
            $data[] = ['id' => $shift->id, 'text' => $shift->name];
        }
        return ['results' => $data];
    }
}
