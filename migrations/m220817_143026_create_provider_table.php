<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provider}}`.
 */
class m220817_143026_create_provider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%provider}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'code' => $this->string(10)->notNull()->unique(),
            'name' => $this->string(150)->notNull(),
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
        $this->dropTable('{{%provider}}');
    }
}
