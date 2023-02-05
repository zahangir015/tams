<?php

namespace app\modules\account\controllers;

use app\components\Helper;
use app\controllers\ParentController;
use app\modules\account\models\ExpenseSubCategory;
use app\modules\account\models\ExpenseSubCategorySearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ExpenseSubCategoryController implements the CRUD actions for ExpenseSubCategory model.
 */
class ExpenseSubCategoryController extends ParentController
{
    /**
     * Lists all ExpenseSubCategory models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new ExpenseSubCategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExpenseSubCategory model.
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
     * Creates a new ExpenseSubCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new ExpenseSubCategory();

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
     * Updates an existing ExpenseSubCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->findModel($uid);

        if($this->request->isPost){
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
     * Deletes an existing ExpenseSubCategory model.
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
     * Finds the ExpenseSubCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return ExpenseSubCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid)
    {
        if (($model = ExpenseSubCategory::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
