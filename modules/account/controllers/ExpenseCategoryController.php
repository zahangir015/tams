<?php

namespace app\modules\account\controllers;

use app\components\GlobalConstant;
use app\components\Helper;
use app\controllers\ParentController;
use app\modules\account\models\BankAccount;
use app\modules\account\models\ExpenseCategory;
use app\modules\account\models\search\ExpenseCategorySearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ExpenseCategoryController implements the CRUD actions for ExpenseCategory model.
 */
class ExpenseCategoryController extends ParentController
{
    /**
     * Lists all ExpenseCategory models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new ExpenseCategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExpenseCategory model.
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
     * Creates a new ExpenseCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new ExpenseCategory();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
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
     * Updates an existing ExpenseCategory model.
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
     * Deletes an existing ExpenseCategory model.
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
     * Finds the ExpenseCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return ExpenseCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): ExpenseCategory
    {
        if (($model = ExpenseCategory::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetCategories($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $categories = ExpenseCategory::query($query);
        $data = [];
        foreach ($categories as $category) {
            $data[] = ['id' => $category->id, 'text' => $category->name];
        }
        return ['results' => $data];
    }
}
