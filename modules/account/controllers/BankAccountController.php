<?php

namespace app\modules\account\controllers;

use app\components\GlobalConstant;
use app\components\Uploader;
use app\components\Utilities;
use app\modules\account\models\BankAccount;
use app\modules\account\models\search\BankAccountSearch;
use app\controllers\ParentController;
use app\modules\account\repositories\BillRepository;
use app\modules\account\services\LedgerService;
use app\modules\account\services\RefundTransactionService;
use app\modules\account\services\TransactionService;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

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
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->findModel($uid),
        ]);
    }

    /**
     * Creates a new BankAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws InvalidConfigException
     */
    public function actionCreate(): Response|string
    {
        $model = new BankAccount();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $file = UploadedFile::getInstance($model, 'logo');
                if (!empty($file)) {
                    $uploadResponse = Uploader::processFile($file, false, 'uploads/bank');
                    if (!$uploadResponse['error']) {
                        Yii::$app->session->setFlash('danger', 'Bank Account logo upload failed - ' . $uploadResponse['message']);
                    }
                    $model->logo = $uploadResponse['name'];
                }

                $model->tag = Json::encode($model->tag);
                if ($model->save()) {
                    // Bank Ledger process
                    $bankLedgerRequestData = [
                        'title' => 'Bank account open',
                        'reference' => $model->name,
                        'refId' => $model->id,
                        'refModel' => BankAccount::class,
                        'subRefId' => null,
                        'subRefModel' => null,
                        'debit' => $model->balance,
                        'credit' => 0
                    ];
                    $bankLedgerRequestResponse = (new LedgerService)->store($bankLedgerRequestData);
                    if ($bankLedgerRequestResponse['error']) {
                        Yii::$app->session->setFlash('danger', 'Bank Ledger creation failed - ' . $bankLedgerRequestResponse['message']);
                    } else {
                        Yii::$app->session->setFlash('success', 'Bank Account created successfully.');
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
        ]);
    }

    /**
     * Updates an existing BankAccount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionUpdate(string $uid)
    {
        $model = $this->findModel($uid);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $oldLogo = $model->logo;
            $model->tag = Json::encode($model->tag);
            $logo = UploadedFile::getInstance($model, 'logo');

            if ($logo) {
                $uploadResponse = Uploader::processFile($logo, false, 'uploads/bank');
                if (!$uploadResponse['error']) {
                    $model->logo = $uploadResponse['name'];
                    if (!empty($oldLogo) && file_exists(getcwd() . '/uploads/bank/' . $oldLogo)) {
                        unlink(getcwd() . '/uploads/bank/' . $oldLogo);
                    }
                }
            } else {
                $model->logo = $oldLogo;
            }

            if (!$model->save()) {
                Yii::$app->session->setFlash('danger', 'Bank update failed - ' . Utilities::processErrorMessages($model->getErrors()));
            } else {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
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
     * */
    public function actionDelete($uid)
    {
        $model = $this->findModel($uid);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        $model->save();
        Yii::$app->session->setFlash('success', 'Successfully Deleted');
        return $this->redirect(['index']);
    }

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
            $data[] = ['id' => $bank->id, 'text' => $bank->name . ' | ' . $bank->accountName . ' | ' . $bank->accountNumber];
        }
        return ['results' => $data];
    }
}
