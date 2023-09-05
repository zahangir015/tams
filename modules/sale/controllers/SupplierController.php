<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\account\services\LedgerService;
use app\modules\sale\models\Supplier;
use app\modules\sale\models\search\SupplierSearch;
use app\controllers\ParentController;
use app\modules\sale\models\SupplierCategory;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends ParentController
{
    /**
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Supplier model.
     * @param string $uid ID
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
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
            $model = new Supplier();
            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    $model->categories = Json::encode($model->categories);
                    if ($model->save()) {
                        // Supplier Ledger process
                        $ledgerRequestData = [
                            'title' => 'New Supplier Add',
                            'reference' => 'New Supplier add',
                            'refId' => $model->id,
                            'refModel' => Supplier::class,
                            'subRefId' => null,
                            'subRefModel' => null,
                            'debit' => 0,
                            'credit' => $model->balance
                        ];
                        $ledgerRequestResponse = (new LedgerService)->store($ledgerRequestData);
                        if ($ledgerRequestResponse['error']) {
                            Yii::$app->session->setFlash('danger', 'Supplier Ledger creation failed - ' . $ledgerRequestResponse['message']);
                        } else {
                            Yii::$app->session->setFlash('success', 'Supplier created successfully.');
                            return $this->redirect(['view', 'uid' => $model->uid]);
                        }
                    }
                    Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'model' => $model,
                'categories' => ArrayHelper::map(SupplierCategory::findAll(['status' => GlobalConstant::ACTIVE_STATUS]), 'name', 'name')
            ]);

    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->findModel($uid);

        if ($model->load($this->request->post())) {
            $model->categories = Json::encode($model->categories);
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Supplier created successfully.');
                return $this->redirect(['view', 'uid' => $model->uid]);
            }

            Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => ArrayHelper::map(SupplierCategory::findAll(['status' => GlobalConstant::ACTIVE_STATUS]), 'name', 'name')
        ]);
    }

    /**
     * Deletes an existing Supplier model.
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
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid ID
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): Supplier
    {
        if (($model = Supplier::find()->where(['uid' => $uid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetSuppliers($query = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $suppliers = Supplier::query($query);
        $data = [];
        foreach ($suppliers as $supplier) {
            $data[] = ['id' => $supplier->id, 'text' => $supplier->name . ' | ' . $supplier->company];
        }
        return ['results' => $data];
    }
}
