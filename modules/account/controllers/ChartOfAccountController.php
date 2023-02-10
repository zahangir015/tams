<?php

namespace app\modules\account\controllers;

use app\components\GlobalConstant;
use app\components\Helper;
use app\controllers\ParentController;
use app\modules\account\models\ChartOfAccount;
use app\modules\account\models\search\ChartOfAccountSearch;
use app\modules\account\repositories\JournalRepository;
use app\modules\account\services\JournalService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ChartOfAccountController implements the CRUD actions for ChartOfAccount model.
 */
class ChartOfAccountController extends ParentController
{
    public JournalService $journalService;
    public JournalRepository $journalRepository;

    public function __construct($id, $module, $config = [])
    {
        $this->journalService = new JournalService();
        $this->journalRepository = new JournalRepository();
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all ChartOfAccount models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new ChartOfAccountSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChartOfAccount model.
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
     * Creates a new ChartOfAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new ChartOfAccount();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            } else {
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
     * Updates an existing ChartOfAccount model.
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
                return $this->redirect(['view', 'uid' => $model->uid]);
            } else {
                Yii::$app->session->setFlash('danger', Helper::processErrorMessages($model->getErrors()));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ChartOfAccount model.
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
     * Finds the ChartOfAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return ChartOfAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(string $uid): ChartOfAccount
    {
        if (($model = ChartOfAccount::findOne(['uid' => $uid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionSearch($query = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $accounts = ChartOfAccount::query($query);
        $data = [];
        foreach ($accounts as $account) {
            $data[] = ['id' => $account->id, 'text' => $account->name. ' | ' .$account->code];
        }
        return ['results' => $data];
    }
}
