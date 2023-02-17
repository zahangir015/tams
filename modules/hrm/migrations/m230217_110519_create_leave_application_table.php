<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%leave_application}}`.
 */
class m230217_110519_create_leave_application_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%leave_application}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeeId' => $this->integer(11)->notNull(),
            'leaveTypeId' => $this->integer(11)->notNull(),
            'numberOfDays' => $this->float()->notNull(),
            'from' => $this->date()->notNull(),
            'to' => $this->date()->notNull(),
            'availableFrom' => $this->time()->null(),
            'description' => $this->text(),
            'remarks' => $this->string()->null(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `leaveTypeId`
        $this->createIndex(
            'idx-leave-application-leaveTypeId',
            'leave_application',
            'leaveTypeId'
        );

        // add foreign key for table `leave_type`
        $this->addForeignKey(
            'fk-leave-application-leaveTypeId',
            'leave_application',
            'leaveTypeId',
            'leave_type',
            'id',
            'CASCADE'
        );

        // creates index for column `employeeId`
        $this->createIndex(
            'idx-leave-application-employeeId',
            'leave_application',
            'employeeId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-leave-application-employeeId',
            'leave_application',
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
        // drop foreign key for table `leaveType`
        $this->dropForeignKey(
            'fk-leave-application-leaveTypeId',
            'leave_application',
        );

        // drop index for column `leaveTypeId`
        $this->dropIndex(
            'idx-leave-application-leaveTypeId',
            'leave_application',
        );

        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-leave-application-employeeId',
            'leave_application',
        );

        // drop index for column `employeeId`
        $this->dropIndex(
            'idx-leave-application-employeeId',
            'leave_application',
        );

        $this->dropTable('{{%leave_application}}');
    }
}
