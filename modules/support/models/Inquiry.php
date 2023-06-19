<?php

namespace app\modules\support\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%inquiry}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $subject
 * @property string $company
 * @property string $phone
 * @property string $email
 * @property string $quire
 * @property string $source
 * @property string $identificationNumber
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class Inquiry extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%inquiry}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'name', 'subject', 'company', 'phone', 'email', 'quire', 'source', 'identificationNumber'], 'required'],
            [['quire', 'source', 'identificationNumber'], 'string'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 30],
            [['subject'], 'string', 'max' => 150],
            [['company'], 'string', 'max' => 60],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 70],
            [['uid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'name' => Yii::t('app', 'Name'),
            'subject' => Yii::t('app', 'Subject'),
            'company' => Yii::t('app', 'Company'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'quire' => Yii::t('app', 'Quire'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }
}
