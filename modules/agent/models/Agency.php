<?php

namespace app\modules\agent\models;

use app\models\City;
use app\models\Company;
use app\models\Country;
use app\modules\admin\models\User;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%agency}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $planId
 * @property string $agentCode
 * @property string $company
 * @property string $address
 * @property int|null $countryId
 * @property int|null $cityId
 * @property string|null $phone
 * @property string|null $email
 * @property string $timeZone
 * @property string $currency
 * @property string|null $title
 * @property string|null $firstName
 * @property string|null $lastName
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property City $city
 * @property Country $country
 * @property Plan $plan
 */
class Agency extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%agency}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['planId', 'agentCode', 'company', 'address', 'timeZone', 'currency'], 'required'],
            [['planId', 'countryId', 'cityId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['agentCode'], 'string', 'max' => 8],
            [['company', 'address', 'phone', 'email', 'timeZone', 'title', 'firstName', 'lastName'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 3],
            [['uid'], 'unique'],
            [['agentCode'], 'unique'],
            [['email'], 'unique'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['cityId' => 'id']],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
            [['planId'], 'exist', 'skipOnError' => true, 'targetClass' => Plan::class, 'targetAttribute' => ['planId' => 'id']],
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
            'planId' => Yii::t('app', 'Plan'),
            'agentCode' => Yii::t('app', 'Agent Code'),
            'company' => Yii::t('app', 'Company'),
            'address' => Yii::t('app', 'Address'),
            'countryId' => Yii::t('app', 'Country'),
            'cityId' => Yii::t('app', 'City'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'timeZone' => Yii::t('app', 'Time Zone'),
            'currency' => Yii::t('app', 'Currency'),
            'title' => Yii::t('app', 'Title'),
            'firstName' => Yii::t('app', 'First Name'),
            'lastName' => Yii::t('app', 'Last Name'),
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

    /**
     * Gets query for [[Plan]].
     *
     * @return ActiveQuery
     */
    public function getPlan(): ActiveQuery
    {
        return $this->hasOne(Plan::class, ['id' => 'planId']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['agencyId' => 'id']);
    }

    /**
     * Gets query for [[Company]].
     *
     * @return ActiveQuery
     */
    public function getCompanyProfile(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['agencyId' => 'id']);
    }
}
