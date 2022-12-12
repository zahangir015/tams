<?php

namespace app\modules\hrm\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hrm\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form of `app\modules\hrm\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userId', 'reportTo', 'gender', 'maritalStatus', 'inProhibition', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'firstName', 'lastName', 'fathersName', 'mothersName', 'dateOfBirth', 'bloodGroup', 'religion', 'nid', 'officialId', 'officialEmail', 'officialPhone', 'permanentAddress', 'presentAddress', 'personalEmail', 'personalPhone', 'contactPersonsName', 'contactPersonsPhone', 'contactPersonsAddress', 'contactPersonsRelation', 'joiningDate', 'confirmationDate', 'jobCategory'], 'safe'],
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
        $query = Employee::find();

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
            'userId' => $this->userId,
            'reportTo' => $this->reportTo,
            'dateOfBirth' => $this->dateOfBirth,
            'gender' => $this->gender,
            'maritalStatus' => $this->maritalStatus,
            'joiningDate' => $this->joiningDate,
            'confirmationDate' => $this->confirmationDate,
            'inProhibition' => $this->inProhibition,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'fathersName', $this->fathersName])
            ->andFilterWhere(['like', 'mothersName', $this->mothersName])
            ->andFilterWhere(['like', 'bloodGroup', $this->bloodGroup])
            ->andFilterWhere(['like', 'religion', $this->religion])
            ->andFilterWhere(['like', 'nid', $this->nid])
            ->andFilterWhere(['like', 'officialId', $this->officialId])
            ->andFilterWhere(['like', 'officialEmail', $this->officialEmail])
            ->andFilterWhere(['like', 'officialPhone', $this->officialPhone])
            ->andFilterWhere(['like', 'permanentAddress', $this->permanentAddress])
            ->andFilterWhere(['like', 'presentAddress', $this->presentAddress])
            ->andFilterWhere(['like', 'personalEmail', $this->personalEmail])
            ->andFilterWhere(['like', 'personalPhone', $this->personalPhone])
            ->andFilterWhere(['like', 'contactPersonsName', $this->contactPersonsName])
            ->andFilterWhere(['like', 'contactPersonsPhone', $this->contactPersonsPhone])
            ->andFilterWhere(['like', 'contactPersonsAddress', $this->contactPersonsAddress])
            ->andFilterWhere(['like', 'contactPersonsRelation', $this->contactPersonsRelation])
            ->andFilterWhere(['like', 'jobCategory', $this->jobCategory]);

        return $dataProvider;
    }
}
