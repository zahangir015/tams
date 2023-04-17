<?php

namespace app\modules\account\controllers;

use app\components\GlobalConstant;
use app\modules\account\models\Journal;
use app\modules\account\models\JournalEntry;
use app\modules\account\models\search\JournalSearch;
use app\controllers\ParentController;
use app\modules\account\repositories\JournalRepository;
use app\modules\account\services\JournalService;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * JournalController implements the CRUD actions for Journal model.
 */
class JournalController extends ParentController
{
    public JournalService $journalService;
    public JournalRepository $journalRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->journalService = new JournalService();
        $this->journalRepository = new JournalRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Journal models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new JournalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Journal model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->journalRepository->findOne(['uid' => $uid], Journal::class, ['journalEntries']),
        ]);
    }

    /**
     * Creates a new Journal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Journal();
        $journalEntry = new JournalEntry();

        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            // Store expense data
            $journalStoreResponse = $this->journalService->storeJournal($requestData, $model);
            if (!$journalStoreResponse['error']) {
                return $this->redirect(['view', 'uid' => $journalStoreResponse['data']->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $journalStoreResponse['message']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'journalEntry' => $journalEntry,
        ]);
    }

    /**
     * Updates an existing Journal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->journalRepository->findOne(['uid' => $uid], Journal::class, ['journalEntries']);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Journal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    /*public function actionDelete(string $uid)
    {
        $this->findModel($uid);

        return $this->redirect(['index']);
    }*/

    public function actionGetReference(): array
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $refModel = $parents[0];
                $out = self::getRefList($refModel);
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    private function getRefList($refModel): array
    {
        $referenceList = $this->journalRepository->findAll(['status' => GlobalConstant::ACTIVE_STATUS, 'agencyId' => Yii::$app->user->identity->agencyId], $refModel, [], false);
        $data = [];
        foreach ($referenceList as $reference) {
            $data[] = ['id' => $reference->id, 'name' => $reference->name];
        }

        return $data;
    }
}
