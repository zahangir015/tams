<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expense_category}}`.
 */
class m230126_040011_create_expense_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expense_category}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(150)->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'createdBy' => $this->integer()->notNull(),
            'updatedAt' => $this->integer(11)->null(),
            'updatedBy' => $this->integer()->null(),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-expense-category-name',
            'expense_category',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `name`
        $this->dropIndex(
            'idx-expense-category-name',
            'expense_category'
        );

        $this->dropTable('{{%expense_category}}');
    }
}
