<?php

namespace app\modules\sale\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\HotelProposal;

/**
 * HotelProposalSearch represents the model behind the search form of `app\modules\sale\models\HotelProposal`.
 */
class HotelProposalSearch extends HotelProposal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'agencyId', 'hotelCategoryId', 'countryId', 'cityId', 'numberOfAdult', 'numberOfChild', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'hotelName', 'hotelAddress', 'amenities', 'notes'], 'safe'],
            [['totalPrice', 'discount'], 'number'],
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
        $query = HotelProposal::find();

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
            'agencyId' => $this->agencyId,
            'hotelCategoryId' => $this->hotelCategoryId,
            'countryId' => $this->countryId,
            'cityId' => $this->cityId,
            'numberOfAdult' => $this->numberOfAdult,
            'numberOfChild' => $this->numberOfChild,
            'totalPrice' => $this->totalPrice,
            'discount' => $this->discount,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'hotelName', $this->hotelName])
            ->andFilterWhere(['like', 'hotelAddress', $this->hotelAddress])
            ->andFilterWhere(['like', 'amenities', $this->amenities])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
