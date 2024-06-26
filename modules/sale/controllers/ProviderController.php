<?php

namespace app\modules\sale\controllers;

use app\modules\sale\models\Provider;
use app\modules\sale\models\search\ProviderSearch;
use app\controllers\ParentController;
use app\modules\sale\models\Supplier;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ProviderController implements the CRUD actions for Provider model.
 */
class ProviderController extends ParentController
{
    /**
     * Lists all Provider models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProviderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Provider model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid)
    {
        return $this->render('view', [
            'model' => $this->findModel($uid),
        ]);
    }

    /**
     * Creates a new Provider model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Provider();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // Cache data update
                $cache = Yii::$app->cache;
                $key = 'provider'.Yii::$app->user->identity->agencyId;
                $cache->delete($key);
                Supplier::query();
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Provider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // Cache data update
            $cache = Yii::$app->cache;
            $key = 'provider'.Yii::$app->user->identity->agencyId;
            $cache->delete($key);
            Supplier::query();

            return $this->redirect(['view', 'uid' => $model->uid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Provider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     *public function actionDelete($id)
     * {
     * $this->findModel($id)->delete();
     *
     * return $this->redirect(['index']);
     * }*/

    /**
     * Finds the Provider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Provider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): Provider
    {
        if (($model = Provider::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetProviders($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $providers = Provider::query($query);
        $data = [];
        foreach ($providers as $provider) {
            $data[] = ['id' => $provider->id, 'text' => $provider->name . ' ('.$provider->code.')'];
        }
        return ['results' => $data];
    }
}
