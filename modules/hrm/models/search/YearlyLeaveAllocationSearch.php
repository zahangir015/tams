<?php

namespace app\modules\hrm\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hrm\models\YearlyLeaveAllocation;

/**
 * YearlyLeaveAllocationSearch represents the model behind the search form of `app\modules\hrm\models\YearlyLeaveAllocation`.
 */
class YearlyLeaveAllocationSearch extends YearlyLeaveAllocation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'leaveTypeId', 'year', 'numberOfDays', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
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
    public function search($params)
    {
        $query = YearlyLeaveAllocation::find();

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
            'leaveTypeId' => $this->leaveTypeId,
            'year' => $this->year,
            'numberOfDays' => $this->numberOfDays,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid]);

        return $dataProvider;
    }
}
