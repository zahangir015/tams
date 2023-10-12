<?php

namespace app\modules\account\controllers;

use app\components\Utilities;
use app\controllers\ParentController;
use app\modules\account\models\BankAccount;
use app\modules\account\models\ContraEntry;
use app\modules\account\models\search\ContraEntrySearch;
use app\modules\account\services\LedgerService;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ContraEntryController implements the CRUD actions for ContraEntry model.
 */
class ContraEntryController extends ParentController
{
    /**
     * Lists all ContraEntry models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new ContraEntrySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContraEntry model.
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
     * Creates a new ContraEntry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new ContraEntry();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // Bank Ledger process
                $bankLedgerRequestData = [
                    'title' => 'Bank Transfer',
                    'reference' => 'Contra Identification Number - ' . $model->identificationNumber,
                    'refId' => $model->bankFrom,
                    'refModel' => BankAccount::class,
                    'subRefId' => $model->id,
                    'subRefModel' => ContraEntry::class,
                    'debit' => 0,
                    'credit' => $model->amount
                ];
                $bankLedgerRequestResponse = (new LedgerService)->store($bankLedgerRequestData);
                if ($bankLedgerRequestResponse['error']) {
                    Yii::$app->session->setFlash('danger', 'Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);;
                }

                // Bank Ledger process
                $bankToLedgerRequestData = [
                    'title' => 'Bank Transfer',
                    'reference' => 'Contra Identification Number - ' . $model->identificationNumber,
                    'refId' => $model->bankTo,
                    'refModel' => BankAccount::class,
                    'subRefId' => $model->id,
                    'subRefModel' => ContraEntry::class,
                    'debit' => $model->amount,
                    'credit' => 0
                ];
                $bankToLedgerRequestResponse = (new LedgerService)->store($bankToLedgerRequestData);
                if ($bankToLedgerRequestResponse['error']) {
                    Yii::$app->session->setFlash('danger', 'Transferred Bank Ledger creation failed - ' . $bankToLedgerRequestResponse['message']);;
                }

                return $this->redirect(['view', 'uid' => $model->uid]);
            } else {
                Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContraEntry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
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
     * Deletes an existing ContraEntry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
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
     * Finds the ContraEntry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return ContraEntry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): ContraEntry
    {
        if (($model = ContraEntry::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
