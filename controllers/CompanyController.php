<?php

namespace app\controllers;

use app\components\AttachmentFile;
use app\components\Helper;
use app\components\Uploader;
use app\models\Company;
use app\models\CompanySearch;
use app\controllers\ParentController;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends ParentController
{
    /**
     * Lists all Company models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
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
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $file = UploadedFile::getInstance($model, 'logo');
                $uploadResponse = Uploader::processFile($file, false, 'uploads/company');
                if (!$uploadResponse['error']) {
                    $model->logo = $uploadResponse['name'];
                    if ($model->save()) {
                        return $this->redirect(['view', 'uid' => $model->uid]);
                    } else {
                        Yii::$app->session->setFlash('danger', Helper::processErrorMessages($model->getErrors()));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'Image upload failed.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
<<<<<<< HEAD
     * @param int $id ID
     * @return string|Response
=======
     * @param string $uid UID
     * @return string|\yii\web\Response
>>>>>>> 52f3e5b26d7a1e3f01deb2844ffcf559f1864a29
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
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
<<<<<<< HEAD
     * @param int $id ID
     * @return Response
=======
     * @param string $uid UID
     * @return \yii\web\Response
>>>>>>> 52f3e5b26d7a1e3f01deb2844ffcf559f1864a29
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($uid)
    {
        if (($model = Company::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
