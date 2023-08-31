<?php

namespace app\models;

use app\components\GlobalConstant;
use app\modules\agent\models\Agency;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CompanySearch represents the model behind the search form of `app\models\Company`.
 */
class CompanySearch extends Company
{
    public $agency;
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'agencyId'], 'integer'],
            [['uid', 'name', 'shortName', 'phone', 'email', 'address', 'logo', 'agency'], 'safe'],
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
        $query = Company::find();

        // add conditions that should always apply here
        $query->joinWith(['agency']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_ASC]],
        ]);

        $dataProvider->sort->attributes['agency'] = [
            'asc' => [Agency::tableName() . '.company' => SORT_ASC],
            'desc' => [Agency::tableName() . '.company' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'shortName', $this->shortName])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', Agency::tableName() . '.company', $this->agency])
            ->andFilterWhere(['like', 'logo', $this->logo]);

        return $dataProvider;
    }
}
