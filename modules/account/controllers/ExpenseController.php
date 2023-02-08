<?php

namespace app\modules\account\controllers;

use app\components\GlobalConstant;
use app\controllers\ParentController;
use app\modules\account\models\Expense;
use app\modules\account\models\search\ExpenseSearch;
use app\modules\account\repositories\ExpenseRepository;
use app\modules\account\services\ExpenseService;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ExpenseController implements the CRUD actions for Expense model.
 */
class ExpenseController extends ParentController
{
    public ExpenseService $expenseService;
    public ExpenseRepository $expenseRepository;

    public function __construct($id, $module, $config = [])
    {
        $this->expenseService = new ExpenseService();
        $this->expenseRepository = new ExpenseRepository();
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all Expense models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ExpenseSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Expense model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->expenseRepository->findOne(['uid' => $uid], Expense::class, ['category', 'subCategory', 'supplier']),
        ]);
    }

    /**
     * Creates a new Expense model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Expense();

        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            // Store expense data
            $expenseStoreResponse = $this->expenseService->storeExpense($requestData, $model);
            if (!$expenseStoreResponse['error']) {
                return $this->redirect(['view', 'uid' => $expenseStoreResponse['data']->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $expenseStoreResponse['message']);
            }

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Expense model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->expenseRepository->findOne(['uid' => $uid], Expense::class, ['category', 'subCategory', 'supplier']);

        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();

            $expenseUpdateResponse = $this->expenseService->updateExpense($requestData, $model);
            if (!$expenseUpdateResponse['error']) {
                return $this->redirect(['view', 'uid' => $expenseStoreResponse['data']->uid]);
            } else {
                Yii::$app->session->setFlash('danger', $expenseStoreResponse['message']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Expense model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Expense model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return Expense the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expense::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetSubCategoryByCategory(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $categoryId = $parents[0];
                $out = $this->expenseService->getSubCategoryList(['categoryId' => $categoryId, 'status' => GlobalConstant::ACTIVE_STATUS]);
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }
}
