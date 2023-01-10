<?php

namespace app\modules\account\controllers;

use app\modules\account\models\RefundTransaction;
use app\modules\account\models\search\RefundTransactionSearch;
use app\modules\account\models\Transaction;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * RefundTransactionController implements the CRUD actions for RefundTransaction model.
 */
class RefundTransactionController extends Controller
{
    /**
     * Lists all RefundTransaction models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RefundTransactionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RefundTransaction model.
     * @param string $uid UID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RefundTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $model = new RefundTransaction();
        $transaction = new Transaction();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'uid' => $model->uid]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'transaction' => $transaction,
        ]);
    }

    /**
     * Updates an existing RefundTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $uid UID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RefundTransaction model.
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
     * Finds the RefundTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $uid UID
     * @return RefundTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RefundTransaction::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionCustomerPending(): array
    {
        $data = Yii::$app->request->get();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $customerId = $data['customerId'];
        if (empty($customerId)) {
            return false;
        }

        $start_date = $end_date = null;
        if (isset($data['dateRange']) && strpos($data['dateRange'], '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $data['dateRange']);
        }

        $pendingServices = Customer::find()
            ->select(['id', 'name', 'company'])
            ->with([
                'refundTickets' => function ($query) use ($start_date, $end_date) {
                    $query->where(['isRefunded' => ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'invoiceId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'refundVisas' => function ($query) use ($start_date, $end_date) {
                    $query->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'invoiceId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'refundHotels' => function ($query) use ($start_date, $end_date) {
                    $query->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'invoiceId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
                'refundHolidays' => function ($query) use ($start_date, $end_date) {
                    $query->where(['<>', 'paymentStatus', ServiceConstant::PAYMENT_STATUS['Full Paid']])
                        ->andWhere(['IS', 'invoiceId', NULL]);
                    if ($start_date && $end_date) {
                        $query->andWhere(['between', 'issueDate', $start_date, $end_date])->orderBy(['issueDate' => SORT_ASC]);
                    }
                },
            ])
            ->where(['id' => $customerId])->one();

        $html = '';
        $key = 1;
        $totalPayable = 0;
        if (!empty($pendingServices->tickets)) {
            foreach ($pendingServices->tickets as $pending) {
                $totalPayable += ($pending->quoteAmount - $pending->receivedAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->receivedAmount,
                        'dueAmount' => ($pending->quoteAmount - $pending->receivedAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->eTicket . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->quoteAmount - $pending->receivedAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->quoteAmount - $pending->receivedAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->hotels)) {
            foreach ($pendingServices->hotels as $pending) {
                $totalPayable += ($pending->quoteAmount - $pending->receivedAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->receivedAmount,
                        'dueAmount' => ($pending->quoteAmount - $pending->receivedAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->voucherId . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->quoteAmount - $pending->receivedAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->quoteAmount - $pending->receivedAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->visas)) {
            foreach ($pendingServices->visas as $pending) {
                $totalPayable += ($pending->quoteAmount - $pending->receivedAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->receivedAmount,
                        'dueAmount' => ($pending->quoteAmount - $pending->receivedAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->identificationNo ?? 'N/A' . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->quoteAmount - $pending->receivedAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->quoteAmount - $pending->receivedAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }
        if (!empty($pendingServices->holidays)) {
            foreach ($pendingServices->holidays as $pending) {
                $totalPayable += ($pending->quoteAmount - $pending->receivedAmount);
                $html .= '<tr>';
                $html .= '<td><input type="checkbox" class="chk" id="chk' . $key . '" name="services[]" value="' . htmlspecialchars(json_encode([
                        'refId' => $pending->id,
                        'refModel' => get_class($pending),
                        'paidAmount' => $pending->receivedAmount,
                        'dueAmount' => ($pending->quoteAmount - $pending->receivedAmount),
                    ])) . '"></td>';
                $html .= '<td>' . $pending->formName() . '</td>';
                $html .= '<td><span class="badge bg-green">' . $pending->identificationNo ?? 'NA' . '</span></td>';
                $html .= '<td>' . $pending->issueDate . '</td>';
                $html .= '<td>' . ($pending->quoteAmount - $pending->receivedAmount) . '<input type="text" class="amount form-control" id="amt' . $key . '" value="' . ($pending->quoteAmount - $pending->receivedAmount) . '" hidden></td>';
                $html .= '</tr>';
                $key++;
            }
        }

        return [
            'html' => $html,
            'totalPayable' => $totalPayable,
            'totalReceivable' => $totalReceivable,
        ];
    }
}
