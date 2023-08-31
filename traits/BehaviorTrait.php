<?php

namespace app\traits;

use app\models\History;
use app\modules\admin\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

trait BehaviorTrait
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ($this->hasAttribute('createdAt')) ? ['createdAt'] : [],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ($this->hasAttribute('updatedAt')) ? ['updatedAt'] : [],
                    //ActiveRecord::EVENT_BEFORE_VALIDATE => ($this->hasAttribute('createdAt')) ? ['createdAt'] : [],
                ],
            ],
        ];
    }

    public function beforeValidate(): bool
    {
        if (isset(Yii::$app->controller->action) && (Yii::$app->controller->action->id != "index")) {
            if ($this->isNewRecord && $this->hasAttribute('createdBy')) {
                $this->createdBy = Yii::$app->user->id ?? 1;
            } elseif(!$this->isNewRecord && $this->hasAttribute('updatedBy')) {
                $this->updatedBy = Yii::$app->user->id ?? 1;
            }

            if ($this->isNewRecord && $this->hasAttribute('uid')) {
                $this->uid = Yii::$app->db->createCommand('select UUID()')->queryScalar();
            }

            if ($this->isNewRecord && $this->hasAttribute('agencyId')) {
                $this->agencyId = $this->agencyId ?: Yii::$app->user->identity->agencyId;
            }
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert): bool
    {
        if ($this->isNewRecord && $this->hasAttribute('createdBy')) {
            $this->createdBy = Yii::$app->user->id ?? 1;
        } elseif(!$this->isNewRecord && $this->hasAttribute('updatedBy')) {
            $this->updatedBy = Yii::$app->user->id ?? 1;
        }

        if ($this->isNewRecord && $this->hasAttribute('updatedBy')) {
            $this->uid = Yii::$app->db->createCommand('select UUID()')->queryScalar();
        }

        if ($this->isNewRecord && $this->hasAttribute('agencyId')) {
            $this->agencyId = $this->agencyId ?: (Yii::$app->user->identity->agencyId ? Yii::$app->user->identity->agencyId : 1);
        }

        if (!$this->isNewRecord) {
            $history = new History();
            $history->snapshot($this->createSnapshot('update'));
        }

        return parent::beforeSave($insert);
    }

    public function beforeDelete(): bool
    {
        $history = new History();
        $history->snapshot($this->createSnapshot('delete'));

        return parent::beforeDelete();
    }

    public function afterFind(): void
    {
        if (isset(Yii::$app->controller->action) && (Yii::$app->controller->action->id == "index" || Yii::$app->controller->action->id == "view")) {
            $this->modifyColumnsData();
        }
        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes): void
    {
        if (isset(Yii::$app->controller->action) && (Yii::$app->controller->action->id == "index" || Yii::$app->controller->action->id == "view")) {
            $this->modifyColumnsData();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function modifyColumnsData(): void
    {
        if ($this->hasAttribute('createdAt')) {
            $this->createdAt = $this->createdAt ? date(Yii::$app->params['dateTimeFormatInView'], $this->createdAt) : null;
        }
        if ($this->hasAttribute('updatedAt')) {
            $this->updatedAt = $this->updatedAt ? date(Yii::$app->params['dateTimeFormatInView'], $this->updatedAt) : null;
        }
        if ($this->hasAttribute('createdBy')) {
            $this->createdBy = ($this->createdBy && $this->creator) ? ucfirst($this->creator['username']) : null;
        }
        if ($this->hasAttribute('updatedBy')) {
            $this->updatedBy = ($this->updatedBy && $this->updater) ? ucfirst($this->updater['username']) : null;
        }
        if ($this->hasAttribute('uid')) {
            $this->uid = $this->uid ?: null;
        }
        if ($this->hasAttribute('uid')) {
            $this->uid = $this->uid ?: null;
        }
    }

    public function getCreator(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'createdBy']);
    }

    public function getUpdater(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'updatedBy']);
    }

    public function createSnapshot($action = 'update'): array
    {
        return [
            'agencyId' => Yii::$app->user->identity->agencyId ?: 1,
            'userId' => Yii::$app->user->id ?: 0,
            'tableName' => $this->tableName(),
            'tableId' => is_numeric($this->getPrimaryKey()) ? $this->getPrimaryKey() : 0,
            'tableData' => Json::encode($this->getAttributes(null)),
            'action' => $action,
            'snapshot' => date('Y-m-d h:i:s')
        ];
    }

}