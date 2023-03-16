<?php

namespace app\modules\hrm\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hrm\models\EmployeePayroll;

/**
 * EmployeePayrollSearch represents the model behind the search form of `app\modules\hrm\models\EmployeePayroll`.
 */
class EmployeePayrollSearch extends EmployeePayroll
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'employeeId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'paymentMode', 'remarks'], 'safe'],
            [['gross', 'tax'], 'number'],
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
        $query = EmployeePayroll::find();

        // add conditions that should always apply here


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
            'gross' => $this->gross,
            'tax' => $this->tax,
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
