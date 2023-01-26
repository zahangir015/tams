<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expense_sub_category}}`.
 */
class m230126_040023_create_expense_sub_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expense_sub_category}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'categoryId' => $this->integer(11)->notNull(),
            'name' => $this->string(150)->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'createdBy' => $this->integer()->notNull(),
            'updatedAt' => $this->integer(11)->null(),
            'updatedBy' => $this->integer()->null(),
        ]);

        // creates index for column `categoryId`
        $this->createIndex(
            'idx-expense-sub-category-categoryId',
            'expense_sub_category',
            'categoryId'
        );

        // add foreign key for table `expense_category`
        $this->addForeignKey(
            'fk-expense-sub-category-categoryId',
            'expense_sub_category',
            'categoryId',
            'expense_category',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `refModel`
        $this->dropIndex(
            'idx-expense-sub-category-categoryId',
            'expense_sub_category'
        );

        // drops foreign key for table `expense_category`
        $this->dropForeignKey(
            'fk-expense-sub-category-categoryId',
            'expense_sub_category'
        );

        $this->dropTable('{{%expense_sub_category}}');
    }
}
