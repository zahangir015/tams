<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier_category}}`.
 */
class m220805_181839_create_supplier_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplier_category}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(30)->notNull()->unique(),
            'status' => $this->boolean()->notNull(),
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
        $this->dropTable('{{%supplier_category}}');
    }
}
