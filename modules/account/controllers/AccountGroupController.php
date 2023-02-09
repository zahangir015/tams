<?php

namespace app\modules\account\controllers;

use app\components\Helper;
use app\modules\account\models\AccountGroup;
use app\modules\account\models\search\AccountGroupSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AccountGroupController implements the CRUD actions for AccountGroup model.
 */
class AccountGroupController extends Controller
{
    /**
     * Lists all AccountGroup models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new AccountGroupSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccountGroup model.
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
     * Creates a new AccountGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new AccountGroup();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing AccountGroup model.
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
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('danger', Helper::processErrorMessages($model->getErrors()));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AccountGroup model.
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

    /**
     * Finds the AccountGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return AccountGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): AccountGroup
    {
        if (($model = AccountGroup::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetTypes($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $types = AccountGroup::query($query);
        $data = [];
        foreach ($types as $type) {
            $data[] = ['id' => $type->id, 'text' => $type->name];
        }
        return ['results' => $data];
    }
}
