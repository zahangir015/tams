<?php

namespace app\modules\agent\controllers;

use app\components\GlobalConstant;
use app\components\Utilities;
use app\modules\admin\models\form\Signup;
use app\modules\admin\models\User;
use app\modules\agent\models\Agency;
use app\modules\agent\models\search\AgencySearch;
use app\controllers\ParentController;
use app\modules\agent\repositories\AgencyRepository;
use app\modules\agent\services\AgencyService;
use Exception;
use Yii;
use yii\web\Response;

/**
 * AgencyController implements the CRUD actions for Agency model.
 */
class AgencyController extends ParentController
{
    public AgencyService $agencyService;
    public AgencyRepository $agencyRepository;

    public function __construct($uid, $module, $config = [])
    {
        $this->agencyService = new AgencyService();
        $this->agencyRepository = new AgencyRepository();
        parent::__construct($uid, $module, $config);
    }

    /**
     * Lists all Agency models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new AgencySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agency model.
     * @param string $uid UID
     * @return string
     */
    public function actionView(string $uid): string
    {
        return $this->render('view', [
            'model' => $this->agencyService->findModel(['uid' => $uid], Agency::class, ['plan']),
        ]);
    }

    /**
     * Creates a new Agency model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new Agency();
        $signup = new Signup();

        if ($this->request->isPost) {
            $dbTransaction = Yii::$app->db->beginTransaction();
            try {
                $requestData = Yii::$app->request->post();

                // Store expense data
                $model->load($requestData);
                //$model = $this->agencyRepository->store($model);
                if (!$model->save()) {
                    throw new Exception(Utilities::processErrorMessages($model->getErrors()));
                }

                if (!$signup->load(Yii::$app->getRequest()->post())) {
                    throw new Exception('User detail loading failed.');
                }

                $signup->agencyId = $model->id;
                $user = $signup->signup();
                if (!$user) {
                    throw new Exception(Utilities::processErrorMessages($signup->getErrors()));
                }

                // the following three lines were added:
                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole($model->plan->name);
                $auth->assign($authorRole, $user->getId());

                $dbTransaction->commit();
                Yii::$app->session->setFlash('success', 'Agent Successfully added');
                return $this->redirect(['view', 'uid' => $model->uid]);

            } catch (Exception $e) {
                Yii::$app->session->setFlash($e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getFile());
                $dbTransaction->rollBack();
            }
        } else {
            $model->loadDefaultValues();
        }


        return $this->render('create', [
            'model' => $model,
            'signup' => $signup,
        ]);
    }

    /**
     * Updates an existing Agency model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     */
    public function actionUpdate(string $uid): Response|string
    {
        $model = $this->agencyService->findModel(['uid' => $uid], Agency::class);
        if ($this->request->isPost) {
            $requestData = Yii::$app->request->post();
            $model->load($requestData);
            $model = $this->agencyRepository->store($model);
            if (!$model->hasErrors()) {
                $users = User::find()->where(['agencyId' => $model->id])->all();
                foreach ($users as $user){
                    // the following three lines were added:
                    $auth = \Yii::$app->authManager;
                    $authorRole = $auth->getRole($model->plan->name);
                    $auth->assign($authorRole, $user->getId());
                }

                return $this->redirect(['view', 'uid' => $model->uid]);
            } else {
                Yii::$app->session->setFlash('danger', Utilities::processErrorMessages($model->getErrors()));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Agency model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $uid UID
     * @return Response
     */
    public function actionDelete(string $uid): Response
    {
        $model = $this->agencyService->findModel(['uid' => $uid], Agency::class);
        $model->status = GlobalConstant::INACTIVE_STATUS;
        $model->save();
        Yii::$app->session->setFlash('success', 'Successfully Deleted');
        return $this->redirect(['index']);
    }
}
