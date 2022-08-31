<?php

class FlightRepository
{
    public function store(Expense $expense): Expense
    {
        $expense->createdBy = Yii::$app->user->id;
        $expense->createdAt = Utils::convertToTimestamp(date('Y-m-d H:i:s'));
        $expense->save();
        return $expense;
    }

    public function findOne(string $uid): ActiveRecord
    {
        return Expense::find()->with(['transactionStatement'])->where(['uid' => $uid])->one();
    }

    public function findAll(string $query): array
    {
        return Expense::find()
            ->select(['id', 'name'])
            ->where(['like', 'name', $query])
            ->andWhere(['status' => 1])
            ->all();
    }

    public function update(Expense $expense): Expense
    {
        $expense->updatedBy = Yii::$app->user->id;
        $expense->updatedAt = Utils::convertToTimestamp(date('Y-m-d H:i:s'));
        $expense->save();
        return $expense;
    }
}