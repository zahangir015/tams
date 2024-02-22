<?php

namespace app\modules\serviceInventory\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\serviceInventory\models\HotelInventory;

/**
 * HotelInventorySearch represents the model behind the search form of `app\modules\serviceInventory\models\HotelInventory`.
 */
class HotelInventorySearch extends HotelInventory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'supplierId', 'countryId', 'cityId', 'hotelCategoryId', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'integer'],
            [['uid', 'hotelName', 'hotelAddress'], 'safe'],
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
        $query = HotelInventory::find();

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
            'supplierId' => $this->supplierId,
            'countryId' => $this->countryId,
            'cityId' => $this->cityId,
            'hotelCategoryId' => $this->hotelCategoryId,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'createdBy' => $this->createdBy,
            'updatedAt' => $this->updatedAt,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'hotelName', $this->hotelName])
            ->andFilterWhere(['like', 'hotelAddress', $this->hotelAddress]);

        return $dataProvider;
    }
}
