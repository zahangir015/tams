<?php

use app\modules\sale\models\Customer;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%customer}}`.
 */
class m230913_041933_add_passportNumber_column_issuedCountry_column_dateOfBirth_column_gender_column_expireDate_column_lastName_column_firstName_column_to_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Customer::tableName(), 'passportNumber', $this->string(50)->null());
        $this->addColumn(Customer::tableName(), 'issuedCountry', $this->string(100)->null());
        $this->addColumn(Customer::tableName(), 'dateOfBirth', $this->date()->null());
        $this->addColumn(Customer::tableName(), 'gender', $this->string(50)->null());
        $this->addColumn(Customer::tableName(), 'expireDate', $this->date()->null());
        $this->addColumn(Customer::tableName(), 'firstName', $this->string(50)->null());
        $this->addColumn(Customer::tableName(), 'lastName', $this->string(50)->null());

        $this->createIndex('idx-customer-passportNumber', Customer::tableName(), 'passportNumber');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-customer-passportNumber', Customer::tableName());

        $this->dropColumn(Customer::tableName(), 'passportNumber');
        $this->dropColumn(Customer::tableName(), 'issuedCountry');
        $this->dropColumn(Customer::tableName(), 'dateOfBirth');
        $this->dropColumn(Customer::tableName(), 'gender');
        $this->dropColumn(Customer::tableName(), 'expireDate');
        $this->dropColumn(Customer::tableName(), 'firstName');
        $this->dropColumn(Customer::tableName(), 'lastName');
    }
}
