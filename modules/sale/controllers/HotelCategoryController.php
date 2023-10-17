<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\controllers\ParentController;
use app\modules\sale\models\hotel\HotelCategory;
use app\modules\sale\models\search\HotelCategorySearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * HotelCategoryController implements the CRUD actions for HotelCategory model.
 */
class HotelCategoryController extends ParentController
{
    /**
     * Lists all HotelCategory models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HotelCategorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HotelCategory model.
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
     * Creates a new HotelCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new HotelCategory();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Hotel Category created successfully.');
                return $this->redirect(['view', 'uid' => $model->uid]);
            }

            Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HotelCategory model.
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
                Yii::$app->session->setFlash('success', 'Hotel Category updated successfully.');
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
            Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HotelCategory model.
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
     * Finds the HotelCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return HotelCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): HotelCategory
    {
        if (($model = HotelCategory::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
