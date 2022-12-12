<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_education}}`.
 */
class m221212_071008_create_employee_education_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_education}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeeId' => $this->integer()->notNull(),
            'instituteName' => $this->string()->notNull(),
            'degreeName' => $this->string()->notNull(),
            'gpa' => $this->integer()->notNull(),
            'passingYear' => $this->integer(4)->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `employeeId`
        $this->createIndex(
            'idx-employee-education-employeeId',
            'employee_education',
            'employeeId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-employee-education-employeeId',
            'employee_education',
            'employeeId',
            'employee',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-employee-education-employeeId',
            'employee_education',
        );
        // drop index for column `employeeId`
        $this->dropIndex(
            'idx-employee-education-employeeId',
            'employee_education',
        );

        $this->dropTable('{{%employee_education}}');
    }
}
