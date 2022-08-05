<?php

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

trait TimestampTrait
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createdAt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updatedAt'],
                ],
            ],
        ];
    }

    public function beforeValidate(): bool
    {
        if (isset(Yii::$app->controller->action) && Yii::$app->controller->action->id != "index" && !Yii::$app->user->isGuest) {
            if ($this->isNewRecord) {
                $this->createdBy = Yii::$app->user->id ?? 1;
            } else {
                $this->updatedBy = Yii::$app->user->id ?? 1;
            }
        }
        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (isset(Yii::$app->controller->action) && (Yii::$app->controller->action->id == "index" || Yii::$app->controller->action->id == "view")) {
            if (isset($this->uid)){
                if (isset($this->createdAt))
                    $this->createdAt = $this->createdAt ? date(Yii::$app->params['dateTimeFormatInView'], $this->createdAt) : null;
                if (isset($this->updatedAt))
                    $this->updatedAt = $this->updatedAt ? date(Yii::$app->params['dateTimeFormatInView'], $this->updatedAt) : null;
                if (isset($this->createdBy))
                    $this->createdBy = $this->createdBy ? ucfirst($this->creator['username']) : null;
                if (isset($this->updatedBy))
                    $this->updatedBy = $this->updatedBy ? ucfirst($this->updater['username']) : null;
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

}