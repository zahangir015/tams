<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agency_account_request}}`.
 */
class m230618_163713_create_agency_account_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agency_account_request}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(30)->notNull(),
            'designation' => $this->string(50)->notNull(),
            'company' => $this->string(60)->notNull(),
            'address' => $this->string(120)->notNull(),
            'countryId' => $this->integer()->notNull(),
            'cityId' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'email' => $this->string(70)->unique()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        $this->createIndex(
            'idx-agency-account-request-name',
            'agency_account_request',
            'company'
        );

        $this->createIndex(
            'idx-agency-account-request-phone',
            'agency_account_request',
            'phone'
        );

        $this->createIndex(
            'idx-agency-account-request-company',
            'agency_account_request',
            'company'
        );

        $this->createIndex(
            'idx-agency-account-request-email',
            'agency_account_request',
            'email'
        );

        $this->createIndex(
            'idx-agency-account-request-countryId',
            'agency_account_request',
            'countryId'
        );

        $this->addForeignKey(
            'fk-agency-account-request-countryId',
            'agency_account_request',
            'countryId',
            'country',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-agency-account-request-cityId',
            'agency_account_request',
            'cityId'
        );

        $this->addForeignKey(
            'fk-agency-account-request-cityId',
            'agency_account_request',
            'cityId',
            'city',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-agency-account-request-name', 'agency_account_request');
        $this->dropIndex('idx-agency-account-request-phone', 'agency_account_request');
        $this->dropIndex('idx-agency-account-request-company', 'agency_account_request');
        $this->dropIndex('idx-agency-account-request-email', 'agency_account_request');

        $this->dropForeignKey('fk-agency-account-request-countryId', 'agency_account_request');
        $this->dropIndex('idx-agency-account-request-countryId', 'agency_account_request');

        $this->dropForeignKey('fk-agency-account-request-cityId', 'agency_account_request');
        $this->dropIndex('idx-agency-account-request-cityId', 'agency_account_request');

        $this->dropTable('{{%agency_account_request}}');
    }
}
