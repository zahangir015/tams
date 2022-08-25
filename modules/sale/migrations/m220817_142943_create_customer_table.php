<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer}}`.
 */
class m220817_142943_create_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'company' => $this->string()->notNull(),
            'customerCode' => $this->string(32)->notNull(),
            'category' => "ENUM('B2C','B2B','B2E') NOT NULL",
            'email' => $this->string()->notNull(),
            'address' => $this->string()->null(),
            'phone' => $this->string(50)->null(),
            'creditModality' => $this->boolean()->defaultValue(0),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'updatedAt' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer}}');
    }
}
