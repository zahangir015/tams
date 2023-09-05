<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\modules\account\services\LedgerService;
use app\modules\sale\models\Customer;
use app\modules\sale\models\search\CustomerSearch;
use app\controllers\ParentController;
use app\modules\sale\models\StarCategory;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends ParentController
{
    /**
     * Lists all Customer models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
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
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Customer();
        $starCategories = ArrayHelper::map(StarCategory::find()->where(['status' => GlobalConstant::ACTIVE_STATUS])->all(), 'id', 'name');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // Customer Ledger process
                $ledgerRequestData = [
                    'title' => 'Service Purchase',
                    'reference' => 'New Customer added - ' . $model->name,
                    'refId' => $model->id,
                    'refModel' => Customer::class,
                    'subRefId' => null,
                    'subRefModel' => null,
                    'debit' => $model->balance,
                    'credit' => 0
                ];
                $ledgerRequestResponse = (new LedgerService)->store($ledgerRequestData);
                if ($ledgerRequestResponse['error']) {
                    Yii::$app->session->setFlash('danger', 'Customer Ledger creation failed - ' . $ledgerRequestResponse['message']);
                } else {
                    Yii::$app->session->setFlash('success', 'Customer Account created successfully.');
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'starCategories' => $starCategories,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'uid' => $model->uid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     **/
    public function actionDelete(string $uid): Response
    {
        $model = $this->findModel($uid);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        $model->save();
        Yii::$app->session->setFlash('success', 'Successfully Deleted');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid ID
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): Customer
    {
        if (($model = Customer::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetCustomers($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $customers = Customer::query($query);
        $data = [];
        foreach ($customers as $customer) {
            $data[] = ['id' => $customer->id, 'text' => $customer->name . ' | ' . $customer->company . ' | ' . $customer->email];
        }
        return ['results' => $data];
    }
}
