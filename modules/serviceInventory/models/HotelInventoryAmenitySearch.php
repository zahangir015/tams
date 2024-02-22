<?php

namespace app\modules\serviceInventory\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\serviceInventory\models\HotelInventoryAmenity;

/**
 * HotelInventoryAmenitySearch represents the model behind the search form of `app\modules\serviceInventory\models\HotelInventoryAmenity`.
 */
class HotelInventoryAmenitySearch extends HotelInventoryAmenity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'hotelInventoryId', 'amenityId'], 'integer'],
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
        $query = HotelInventoryAmenity::find();

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
            'hotelInventoryId' => $this->hotelInventoryId,
            'amenityId' => $this->amenityId,
        ]);

        return $dataProvider;
    }
}
