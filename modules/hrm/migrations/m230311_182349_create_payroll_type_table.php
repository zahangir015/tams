<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payroll_type}}`.
 */
class m230311_182349_create_payroll_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payroll_type}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(150)->notNull(),
            'amountType' => $this->boolean()->notNull(),
            'calculatingMethod' => $this->boolean()->notNull(),
            'amount' => $this->double()->notNull()->defaultValue(0),
            'category' => "ENUM('Salary', 'Allowance')",
            'order' => $this->smallInteger(2)->notNull()->unique(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-payroll-type-name',
            'payroll_type',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop index for column `name`
        $this->dropIndex(
            'idx-payroll-type-name',
            'payroll_type',
        );

        $this->dropTable('{{%payroll_type}}');
    }
}
