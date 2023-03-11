<?php

namespace app\modules\hrm\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hrm\models\Attendance;

/**
 * AttendanceSearch represents the model behind the search form of `app\modules\hrm\models\Attendance`.
 */
class AttendanceSearch extends Attendance
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'employeeId', 'shiftId', 'leaveTypeId', 'leaveApplicationId', 'rosterId', 'isAbsent', 'isLate', 'isEarlyOut', 'status', 'createdBy', 'updatedBy', 'createdAt', 'updatedAt'], 'integer'],
            [['uid', 'date', 'entry', 'exit', 'totalLateInTime', 'totalEarlyOutTime', 'totalWorkingHours', 'overTime', 'remarks', 'employeeNote'], 'safe'],
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
        $query = Attendance::find();

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
            'shiftId' => $this->shiftId,
            'leaveTypeId' => $this->leaveTypeId,
            'leaveApplicationId' => $this->leaveApplicationId,
            'rosterId' => $this->rosterId,
            'date' => $this->date,
            'entry' => $this->entry,
            'exit' => $this->exit,
            'isAbsent' => $this->isAbsent,
            'isLate' => $this->isLate,
            'isEarlyOut' => $this->isEarlyOut,
            'totalLateInTime' => $this->totalLateInTime,
            'totalEarlyOutTime' => $this->totalEarlyOutTime,
            'totalWorkingHours' => $this->totalWorkingHours,
            'overTime' => $this->overTime,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'employeeNote', $this->employeeNote]);

        return $dataProvider;
    }
}
