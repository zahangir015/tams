<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expense}}`.
 */
class m230126_040042_create_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expense}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'categoryId' => $this->integer(11)->notNull(),
            'subCategoryId' => $this->integer(11)->notNull(),
            'supplierId' => $this->integer(11)->notNull(),
            'name' => $this->string(150)->notNull(),
            'accruingMonth' => $this->date(150)->null(),
            'timingOfExp' => "ENUM('Monthly', 'Prepaid', 'Accrued')",
            'notes' => $this->text()->null(),
            'status' => $this->tinyInteger()->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedAt' => $this->integer(11)->null(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
        ]);

        // creates index for column `categoryId`
        $this->createIndex(
            'idx-expense-categoryId',
            'expense',
            'categoryId'
        );

        // add foreign key for table `expense_category`
        $this->addForeignKey(
            'fk-expense-categoryId',
            'expense',
            'categoryId',
            'expense_category',
            'id',
            'CASCADE'
        );

        // creates index for column `subCategoryId`
        $this->createIndex(
            'idx-expense-subCategoryId',
            'expense',
            'categoryId'
        );

        // add foreign key for table `expense_sub_category`
        $this->addForeignKey(
            'fk-expense-subCategoryId',
            'expense',
            'subCategoryId',
            'expense_sub_category',
            'id',
            'CASCADE'
        );

        // creates index for column `supplierId`
        $this->createIndex(
            'idx-expense-supplierId',
            'expense',
            'supplierId'
        );

        // add foreign key for table `supplier`
        $this->addForeignKey(
            'fk-expense-supplierId',
            'expense',
            'supplierId',
            'supplier',
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
            'idx-expense-categoryId',
            'expense'
        );

        // drops foreign key for table `expense_category`
        $this->dropForeignKey(
            'fk-expense-categoryId',
            'expense'
        );

        // drops index for column `refModel`
        $this->dropIndex(
            'idx-expense-categoryId',
            'expense'
        );

        // drops foreign key for table `expense_sub_category`
        $this->dropForeignKey(
            'fk-expense-categoryId',
            'expense'
        );

        // drops index for column `refModel`
        $this->dropIndex(
            'idx-expense-supplierId',
            'expense'
        );

        // drops foreign key for table `suppliers`
        $this->dropForeignKey(
            'fk-expense-supplierId',
            'expense'
        );

        $this->dropTable('{{%expense}}');
    }
}
