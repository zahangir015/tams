<?php

namespace app\modules\hrm\models\search;

use app\components\GlobalConstant;
use app\modules\hrm\models\Employee;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hrm\models\LeaveApplication;

/**
 * LeaveApplicationSearch represents the model behind the search form of `app\modules\hrm\models\LeaveApplication`.
 */
class AppliedLeaveApplicationSearch extends LeaveApplication
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'employeeId', 'leaveTypeId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'from', 'to', 'availableFrom', 'description', 'remarks'], 'safe'],
            [['numberOfDays'], 'number'],
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
        $query = LeaveApplication::find();

        // add conditions that should always apply here
        $query->where([self::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName().'.agencyId' => Yii::$app->user->identity->agencyId]);

        $employee = Employee::findOne(['userId' => Yii::$app->user->id]);
        $query->andWhere(['employeeId' => ($employee) ? $employee->id : null]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['from' => SORT_DESC]]
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
            'leaveTypeId' => $this->leaveTypeId,
            'numberOfDays' => $this->numberOfDays,
            'from' => $this->from,
            'to' => $this->to,
            'availableFrom' => $this->availableFrom,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
