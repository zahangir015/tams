<?php

namespace app\modules\sale\controllers;

use app\components\Helper;
use app\modules\sale\models\SupplierCategory;
use app\modules\sale\models\search\SupplierCategorySearch;
use app\controllers\ParentController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SupplierCategoryController implements the CRUD actions for SupplierCategory model.
 */
class SupplierCategoryController extends ParentController
{

    /**
     * Lists all SupplierCategory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierCategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupplierCategory model.
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
     * Creates a new SupplierCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new SupplierCategory();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Supplier Category created successfully.');
                return $this->redirect(['view', 'uid' => $model->uid]);
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
     * Updates an existing SupplierCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Supplier Category updated successfully.');
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
            Yii::$app->session->setFlash('danger', Helper::processErrorMessages($model->getErrors()));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SupplierCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * public function actionDelete($uid)
     * {
     * $this->findModel($uid)->delete();
     *
     * return $this->redirect(['index']);
     * }*/

    /**
     * Finds the SupplierCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return SupplierCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): SupplierCategory
    {
        if (($model = SupplierCategory::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
