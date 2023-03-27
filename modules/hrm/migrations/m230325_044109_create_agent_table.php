<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agent}}`.
 */
class m230325_044109_create_agent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agent}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'agentCode' => $this->string(8)->notNull()->unique(),
            'company' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'countryId' => $this->integer()->null(),
            'cityId' => $this->integer()->null(),
            'planId' => $this->integer()->notNull(),
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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%agent}}');
    }
}
