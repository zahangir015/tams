<?php

namespace app\modules\hrm\models\search;

use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hrm\models\Payslip;

/**
 * PayslipSearch represents the model behind the search form of `app\modules\hrm\models\Payslip`.
 */
class PayslipSearch extends Payslip
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'employeeId', 'month', 'year', 'processStatus', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid', 'paymentMode', 'remarks'], 'safe'],
            [['gross', 'tax', 'lateFine', 'totalAdjustment', 'totalDeduction', 'totalPaid'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Payslip::find();

        // add conditions that should always apply here
        $query->where([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'employeeId' => $this->employeeId,
            'month' => $this->month,
            'year' => $this->year,
            'gross' => $this->gross,
            'tax' => $this->tax,
            'lateFine' => $this->lateFine,
            'totalAdjustment' => $this->totalAdjustment,
            'totalDeduction' => $this->totalDeduction,
            'totalPaid' => $this->totalPaid,
            'processStatus' => $this->processStatus,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'paymentMode', $this->paymentMode])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
