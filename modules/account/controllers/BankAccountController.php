<?php

namespace app\modules\account\controllers;

use app\components\Helper;
use app\modules\account\models\BankAccount;
use app\modules\account\models\search\BankAccountSearch;
use app\controllers\ParentController;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * BankAccountController implements the CRUD actions for BankAccount model.
 */
class BankAccountController extends ParentController
{
    /**
     * Lists all BankAccount models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BankAccountSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BankAccount model.
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
     * Creates a new BankAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new BankAccount();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->tag = Json::encode($model->tag);
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Bank Account created successfully.');
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
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
     * Updates an existing BankAccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BankAccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     *
     * public function actionDelete($uid)
    {
        $this->findModel($uid)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the BankAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return BankAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): BankAccount
    {
        if (($model = BankAccount::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetBanks($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $banks = BankAccount::query($query);
        $data = [];
        foreach ($banks as $bank) {
            $data[] = ['id' => $bank->id, 'text' => $bank->name . ' | ' . $bank->accountName . ' | ' . $bank->accountnumber];
        }
        return ['results' => $data];
    }
}
