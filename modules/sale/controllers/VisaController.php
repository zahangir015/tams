<?php

namespace app\modules\sale\controllers;

use app\modules\sale\models\visa\Visa;
use app\modules\sale\models\visa\VisaSupplier;
use app\modules\sale\models\VisaSearch;
use app\controllers\ParentController;
use yii\bootstrap4\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VisaController implements the CRUD actions for Visa model.
 */
class VisaController extends ParentController
{
    /**
     * Lists all Visa models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new VisaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Visa model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Visa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Visa();
        $visaSupplier = new VisaSupplier();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'visaSupplier' => $visaSupplier,
        ]);
    }

    /**
     * Updates an existing Visa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Visa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Visa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Visa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Visa::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionAddSupplier($row): string
    {
        $model = new Visa();
        $visaSupplier = new VisaSupplier();
        return $this->renderAjax('supplier', [
            'row' => $row,
            'model' => $model,
            'visaSupplier' => $visaSupplier,
            'form' => ActiveForm::begin(['class' => 'form'])
        ]);
    }
}
