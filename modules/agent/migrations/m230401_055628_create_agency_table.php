<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agency}}`.
 */
class m230401_055628_create_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agency}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'planId' => $this->integer()->notNull(),
            'agentCode' => $this->string(8)->notNull()->unique(),
            'company' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'countryId' => $this->integer()->null(),
            'cityId' => $this->integer()->null(),
            'phone' => $this->string()->null(),
            'email' => $this->string()->unique()->null(),
            'timeZone' => $this->string()->notNull(),
            'currency' => $this->string(3)->notNull(),
            'title' => $this->string()->null(),
            'firstName' => $this->string()->null(),
            'lastName' => $this->string()->null(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        $this->createIndex(
            'idx-agency-agentCode',
            'agency',
            'agentCode'
        );

        $this->createIndex(
            'idx-agency-company',
            'agency',
            'company'
        );

        $this->createIndex(
            'idx-agency-planId',
            'agency',
            'planId'
        );

        $this->addForeignKey(
            'fk-agency-planId',
            'agency',
            'planId',
            'plan',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-agency-countryId',
            'agency',
            'countryId'
        );

        $this->addForeignKey(
            'fk-agency-countryId',
            'agency',
            'countryId',
            'country',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-agency-cityId',
            'agency',
            'cityId'
        );

        $this->addForeignKey(
            'fk-agency-cityId',
            'agency',
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
        $this->dropIndex('idx-agency-agentCode', 'agency');
        $this->dropIndex('idx-agency-company', 'agency');

        $this->dropForeignKey('fk-agency-planId', 'agency');
        $this->dropIndex('idx-agency-planId', 'agency');

        $this->dropForeignKey('fk-agency-countryId', 'agency');
        $this->dropIndex('idx-agency-countryId', 'agency');

        $this->dropForeignKey('fk-agency-cityId', 'agency');
        $this->dropIndex('idx-agency-cityId', 'agency');

        $this->dropTable('{{%agency}}');
    }
}
