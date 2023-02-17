<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%yearly_leave_allocation}}`.
 */
class m230217_110121_create_yearly_leave_allocation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%yearly_leave_allocation}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'leaveTypeId' => $this->integer(11)->notNull(),
            'year' => $this->integer(4)->notNull(),
            'numberOfDays' => $this->tinyInteger(1)->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `leaveTypeId`
        $this->createIndex(
            'idx-yearly-leave-allocation-leaveTypeId',
            'yearly_leave_allocation',
            'leaveTypeId'
        );

        // add foreign key for table `department`
        $this->addForeignKey(
            'fk-yearly-leave-allocation-leaveTypeId',
            'yearly_leave_allocation',
            'leaveTypeId',
            'leave_type',
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
            'fk-yearly-leave-allocation-leaveTypeId',
            'yearly_leave_allocation',
        );

        // drop index for column `leaveTypeId`
        $this->dropIndex(
            'idx-yearly-leave-allocation-leaveTypeId',
            'yearly_leave_allocation',
        );

        $this->dropTable('{{%yearly_leave_allocation}}');
    }
}
