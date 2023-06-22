<?php

namespace app\modules\agent\models;

use app\models\City;
use app\models\Country;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%agency_account_request}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $designation
 * @property string $company
 * @property string $address
 * @property int $countryId
 * @property int $cityId
 * @property string $phone
 * @property string $email
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property City $city
 * @property Country $country
 */
class AgencyAccountRequest extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%agency_account_request}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'name', 'company', 'address', 'countryId', 'cityId', 'phone', 'email'], 'required'],
            [['countryId', 'cityId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 30],
            [['designation'], 'string', 'max' => 50],
            [['company'], 'string', 'max' => 60],
            [['address'], 'string', 'max' => 120],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 70],
            [['uid'], 'unique'],
            [['email'], 'unique'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['cityId' => 'id']],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
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
            'designation' => Yii::t('app', 'Designation'),
            'company' => Yii::t('app', 'Company'),
            'address' => Yii::t('app', 'Address'),
            'countryId' => Yii::t('app', 'Country'),
            'cityId' => Yii::t('app', 'City'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'cityId']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }
}
