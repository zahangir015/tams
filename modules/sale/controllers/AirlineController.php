<?php

namespace app\modules\sale\controllers;

use app\modules\sale\models\Airline;
use app\modules\sale\models\search\AirlineSearch;
use app\controllers\ParentController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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

        if ($this->request->isPost && $model->load() {
            $requestedData = $this->request->post());

            if (($model->commission != $requestedData['commission']) ||
                ($model->incentive != $requestedData['incentive']) ||
                ($model->govtTax != $requestedData['govtTax']) ||
                ($model->serviceCharge != $requestedData['serviceCharge'])) {
                $existAmountValues = AirlineHistory::organizeHistoryData($model);
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $newAirlineHistory = new AirlineHistory();
                    $newAirlineHistory->load(['AirlineHistory' => $existAmountValues]);
                    if ($newAirlineHistory->save()) {
                        $model->load($request->post());
                        $model->save();
                        $isValid = true;
                        $transaction->commit();
                    }
                } catch (\yii\base\Exception $e) {
                    $isValid = false;
                    $transaction->rollBack();
                }
            } else {
                $model->load($request->post());
                if ($model->save()) {
                    $isValid = true;
                }
            }

            return $this->redirect(['view', 'uid' => $model->uid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Airline model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     *public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the Airline model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Airline the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): Airline
    {
        if (($model = Airline::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
