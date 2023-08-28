<?php

namespace app\modules\admin\models\searchs;

use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * User represents the model behind the search form about `app\modules\admin\models\User`.
 */
class User extends Model
{
    public $id;
    public $username;
    public $email;
    public $status;
    public $agencyId;
    public $agency;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'agencyId'], 'integer'],
            [['username', 'email', 'agency'], 'safe'],
        ];
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
        /* @var $query ActiveQuery */
        $class = Yii::$app->getUser()->identityClass ? : 'app\modules\admin\models\User';
        $query = $class::find()->joinWith(['agency']);

        if(Yii::$app->user->identity->agencyId){
            $query->where([\app\modules\admin\models\User::tableName().'.status' => GlobalConstant::ACTIVE_USER_STATUS])
                ->andWhere(['agencyId' => Yii::$app->user->identity->agencyId]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
