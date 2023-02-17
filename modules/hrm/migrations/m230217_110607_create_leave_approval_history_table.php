<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%leave_approval_history}}`.
 */
class m230217_110607_create_leave_approval_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%leave_approval_history}}', [
            'id' => $this->primaryKey(),
            'leaveApplicationId' => $this->integer()->notNull(),
            'requestedTo' => $this->integer(11)->notNull(),
            'approvalLevel' => $this->tinyInteger(1)->notNull(),
            'approvalStatus' => "ENUM('Pending', 'Approved', 'Cancelled') DEFAULT 'Pending'",
            'remarks' => $this->string()->null(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `leaveApplicationId`
        $this->createIndex(
            'idx-leave-approval-history-leaveApplicationId',
            'leave_approval_history',
            'leaveApplicationId'
        );

        // add foreign key for table `leave_application`
        $this->addForeignKey(
            'fk-leave-approval-history-leaveApplicationId',
            'leave_approval_history',
            'leaveApplicationId',
            'leave_application',
            'id',
            'CASCADE'
        );

        // creates index for column `requestedTo`
        $this->createIndex(
            'idx-leave-approval-history-requestedTo',
            'leave_approval_history',
            'requestedTo'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-leave-approval-history-requestedTo',
            'leave_approval_history',
            'requestedTo',
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
        // drop foreign key for table `leave_application`
        $this->dropForeignKey(
            'fk-leave-approval-history-leaveApplicationId',
            'leave_approval_history',
        );

        // drop index for column `leaveApplicationId`
        $this->dropIndex(
            'idx-leave-approval-history-leaveApplicationId',
            'leave_approval_history',
        );

        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-leave-approval-history-requestedTo',
            'leave_approval_history',
        );

        // drop index for column `requestedTo`
        $this->dropIndex(
            'idx-leave-approval-history-requestedTo',
            'leave_approval_history',
        );

        $this->dropTable('{{%leave_approval_history}}');
    }
}
