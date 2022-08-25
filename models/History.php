<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%history}}".
 *
 * @property int $id
 * @property int $userId
 * @property string $tableName
 * @property int $tableId
 * @property string $tableData
 * @property string $snapshot
 * @property string|null $action
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'tableName', 'tableId', 'tableData', 'snapshot'], 'required'],
            [['userId', 'tableId'], 'integer'],
            [['tableData', 'action'], 'string'],
            [['snapshot'], 'safe'],
            [['tableName'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
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
}
