<?php

namespace app\modules\serviceInventory\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\serviceInventory\models\HotelInventoryRoomDetail;

/**
 * HotelInventoryRoomDetailSearch represents the model behind the search form of `app\modules\serviceInventory\models\HotelInventoryRoomDetail`.
 */
class HotelInventoryRoomDetailSearch extends HotelInventoryRoomDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'roomTypeId', 'meal', 'extraBed', 'numberOfRoom', 'isAvailable', 'cancelationPolicy', 'perNightSelling', 'transfer'], 'integer'],
            [['uid', 'currency', 'priceValidity', 'transferDetails'], 'safe'],
            [['perNightCost'], 'number'],
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
        $query = HotelInventoryRoomDetail::find();

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
            'roomTypeId' => $this->roomTypeId,
            'meal' => $this->meal,
            'extraBed' => $this->extraBed,
            'numberOfRoom' => $this->numberOfRoom,
            'isAvailable' => $this->isAvailable,
            'cancelationPolicy' => $this->cancelationPolicy,
            'perNightCost' => $this->perNightCost,
            'perNightSelling' => $this->perNightSelling,
            'priceValidity' => $this->priceValidity,
            'transfer' => $this->transfer,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'transferDetails', $this->transferDetails]);

        return $dataProvider;
    }
}
