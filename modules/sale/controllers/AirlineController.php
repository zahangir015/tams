<?php

namespace app\modules\sale\controllers;

use app\components\GlobalConstant;
use app\modules\sale\models\Airline;
use app\modules\sale\models\AirlineHistory;
use app\modules\sale\models\search\AirlineSearch;
use app\controllers\ParentController;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AirlineController implements the CRUD actions for Airline model.
 */
class AirlineController extends ParentController
{
    /**
     * Lists all Airline models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AirlineSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Airline model.
     * @param string $uid ID
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
     * Creates a new Airline model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Airline();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Airline model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost) {
            $dbTransaction = Yii::$app->db->beginTransaction();
            try {
                $requestedData = $this->request->post();
                // History processing
                if (($model->commission != $requestedData['Airline']['commission']) ||
                    ($model->incentive != $requestedData['Airline']['incentive']) ||
                    ($model->govTax != $requestedData['Airline']['govTax']) ||
                    ($model->serviceCharge != $requestedData['Airline']['serviceCharge'])) {
                    $newAirlineHistoryStoreResponse = AirlineHistory::store($model);
                    if ($newAirlineHistoryStoreResponse['error']) {
                        Yii::$app->session->setFlash('danger', $newAirlineHistoryStoreResponse['message']);
                        $dbTransaction->rollBack();
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }

                if ($model->load($requestedData) && $model->save()) {
                    $dbTransaction->commit();
                    return $this->redirect(['view', 'uid' => $model->uid]);
                }

                $dbTransaction->rollBack();
            } catch (Exception $e) {
                Yii::$app->session->setFlash('danger', $e->getFile() . ' ' . $e->getLine() . '' . $e->getMessage());
                $dbTransaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Airline model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * */
    public function actionDelete(string $uid): Response
    {
        $model = $this->findModel($uid);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        $model->save();
        Yii::$app->session->setFlash('success', 'Successfully Deleted');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Airline model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): array|ActiveRecord
    {
        if (($model = Airline::find()->with(['supplier'])->where(['uid' => $uid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetAirlines($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $airlines = Airline::query($query);
        $data = [];
        foreach ($airlines as $airline) {
            $data[] = ['id' => $airline->id, 'text' => $airline->name . ' (' . $airline->code . ')'];
        }
        return ['results' => $data];
    }

    public function actionGetAirlineDetails(int $airlineId): array|ActiveRecord|null
    {
        return Airline::find()
            ->select(['id', 'commission', 'incentive', 'govTax', 'serviceCharge'])
            ->where(['id' => $airlineId])
            ->one();
    }
}
