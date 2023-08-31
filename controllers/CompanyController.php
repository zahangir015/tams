<?php

namespace app\controllers;

use app\components\AttachmentFile;
use app\components\Utilities;
use app\components\Uploader;
use app\models\Company;
use app\models\CompanySearch;
use app\controllers\ParentController;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
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
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->findModel($uid),
        ]);
    }

    /**
     * Displays a single Company model.
     * @return string
     */
    public function actionCompanyProfile(): string
    {
        $company = Company::findOne(['agencyId' => Yii::$app->user->identity->agencyId]);
        return $this->render('view', [
            'model' => $company,
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
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
                        Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'Image upload failed - '. $uploadResponse['message']);
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
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $oldLogo = $model->logo;
            $logo = UploadedFile::getInstance($model, 'logo');

            if ($logo) {
                $uploadResponse = Uploader::processFile($logo, false, 'uploads/company');
                if (!$uploadResponse['error']) {
                    $model->logo = $uploadResponse['name'];
                    if (!empty($oldLogo) && file_exists(getcwd() . '/uploads/company/' . $oldLogo)) {
                        unlink(getcwd() . '/uploads/company/' . $oldLogo);
                    }
                }
            } else {
                $model->logo = $oldLogo;
            }

            if (!$model->save()) {
                Yii::$app->session->setFlash('danger', 'Company profile update failed - ' . Utilities::processErrorMessages($model->getErrors()));
            } else {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.u
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(string $uid)
    {
        $this->findModel($uid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): Company
    {
        if (($model = Company::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
