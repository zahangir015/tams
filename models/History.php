<?php

namespace app\models;

use app\components\Utilities;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%history}}".
 *
 * @property int $id
 * @property int $userId
 * @property int $agencyId
 * @property string $tableName
 * @property int $tableId
 * @property string $tableData
 * @property string $snapshot
 * @property string|null $action
 */
class History extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId', 'tableName', 'tableId', 'tableData', 'snapshot'], 'required'],
            [['userId', 'tableId', 'agencyId'], 'integer'],
            [['tableData', 'action'], 'string'],
            [['snapshot'], 'safe'],
            [['tableName'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'tableName' => 'Table Name',
            'tableId' => 'Table ID',
            'tableData' => 'Table Data',
            'snapshot' => 'Snapshot',
            'action' => 'Action',
        ];
    }

    public function snapshot($snapshot = array()): bool
    {
        if (empty($snapshot)) {
            Yii::$app->session->setFlash('danger','Empty snapshot data - application.models.History');
            return false;
        }
        else {
            $this->setAttributes($snapshot);
            if ($this->save()) {
                return true;
            }
            else {
                Yii::$app->session->setFlash('danger','Error while saving snapshot - application.models.History - '.Utilities::processErrorMessages($this->getErrors()));
                return false;
            }
        }
    }

}
