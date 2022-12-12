<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_designation}}`.
 */
class m221212_071030_create_employee_designation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_designation}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeeId' => $this->integer()->notNull(),
            'departmentId' => $this->integer()->notNull(),
            'designationId' => $this->integer()->notNull(),
            'branchId' => $this->integer()->notNull(),
            'startDate' => $this->date()->notNull(),
            'endDate' => $this->date()->notNull(),
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
        $this->dropTable('{{%employee_designation}}');
    }
}
