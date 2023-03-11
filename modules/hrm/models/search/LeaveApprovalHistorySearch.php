<?php

namespace app\modules\hrm\models\search;

use app\components\GlobalConstant;
use app\modules\hrm\models\Employee;
use app\modules\hrm\models\LeaveApplication;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hrm\models\LeaveApprovalHistory;

/**
 * LeaveApprovalHistorySearch represents the model behind the search form of `app\modules\hrm\models\LeaveApprovalHistory`.
 */
class LeaveApprovalHistorySearch extends LeaveApprovalHistory
{
    public $leaveApplication;
    public $employee;
    public $leaveType;
    public $numberOfDays;
    public $from;
    public $to;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'leaveApplicationId', 'requestedTo', 'approvalLevel', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['approvalStatus', 'remarks', 'uid', 'employee', 'leaveType', 'numberOfDays', 'from', 'to'], 'safe'],
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
        $query = LeaveApprovalHistory::find();

        // add conditions that should always apply here
        $query->joinWith(['leaveApplication' => function ($query) {
            return $query->with(['employee', 'leaveType']);
        }])->where([LeaveApprovalHistory::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS]);

        $employee = Employee::findOne(['userId' => Yii::$app->user->id]);
        if ($employee) {
            $query->andWhere(['requestedTo' => $employee->id]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['employee'] = [
            'asc' => [LeaveApplication::tableName() . '.employeeId' => SORT_ASC],
            'desc' => [LeaveApplication::tableName() . '.employeeId' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['leaveType'] = [
            'asc' => [LeaveApplication::tableName() . '.leaveTypeId' => SORT_ASC],
            'desc' => [LeaveApplication::tableName() . '.leaveTypeId' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['numberOfDays'] = [
            'asc' => [LeaveApplication::tableName() . '.numberOfDays' => SORT_ASC],
            'desc' => [LeaveApplication::tableName() . '.numberOfDays' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['from'] = [
            'asc' => [LeaveApplication::tableName() . '.from' => SORT_ASC],
            'desc' => [LeaveApplication::tableName() . '.from' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['to'] = [
            'asc' => [LeaveApplication::tableName() . '.to' => SORT_ASC],
            'desc' => [LeaveApplication::tableName() . '.to' => SORT_DESC],
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
            'leaveApplicationId' => $this->leaveApplicationId,
            'requestedTo' => $this->requestedTo,
            'approvalLevel' => $this->approvalLevel,
            LeaveApplication::tableName() . '.employeeId' => $this->employee,
            LeaveApplication::tableName() . '.leaveTypeId' => $this->leaveType,
            LeaveApplication::tableName() . '.from' => $this->from,
            LeaveApplication::tableName() . '.to' => $this->to,
            LeaveApplication::tableName() . '.numberOfDays' => $this->numberOfDays,
        ]);

        $query->andFilterWhere(['like', 'approvalStatus', $this->approvalStatus])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
