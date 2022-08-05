<?php

trait UniqueIdentifierTrait
{
    public function beforeSave($insert): bool
    {
        if ($this->isNewRecord && isset(Yii::$app->controller->action)) {
            $this->uid = new yii\db\Expression('UUID()');
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (isset(Yii::$app->controller->action) && (Yii::$app->controller->action->id == "index" || Yii::$app->controller->action->id == "view")) {
            if (isset($this->uid)) {
                $this->uid = $this->uid ?: null;
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

}