<?php

namespace app\modules\account\models\search;

use app\components\GlobalConstant;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\account\models\ChartOfAccount;

/**
 * ChartOfAccountSearch represents the model behind the search form of `app\modules\account\models\ChartOfAccount`.
 */
class ChartOfAccountSearch extends ChartOfAccount
{

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'accountTypeId', 'accountGroupId', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy', 'agencyId'], 'integer'],
            [['uid', 'code', 'name', 'description', 'reportType'], 'safe'],
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
        $query = ChartOfAccount::find();

        // add conditions that should always apply here
        $query->joinWith(['accountType', 'accountGroup'])
            ->where([self::tableName().'.status' => GlobalConstant::ACTIVE_STATUS]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
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
            'accountTypeId' => $this->accountTypeId,
            'accountGroupId' => $this->accountGroupId,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'createdBy' => $this->createdBy,
            'updatedAt' => $this->updatedAt,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'reportType', $this->reportType]);

        return $dataProvider;
    }
}
