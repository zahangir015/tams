<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%leave_approval_policy}}`.
 */
class m230217_110503_create_leave_approval_policy_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%leave_approval_policy}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'approvalLevel' => $this->tinyInteger(1)->notNull(),
            'employeeId' => $this->integer(11)->notNull(),
            'requestedTo' => $this->integer(11)->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `employeeId`
        $this->createIndex(
            'idx-leave-approval-policy-employeeId',
            'leave_approval_policy',
            'employeeId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-leave-approval-policy-employeeId',
            'leave_approval_policy',
            'employeeId',
            'employee',
            'id',
            'CASCADE'
        );

        // creates index for column `requestedTo`
        $this->createIndex(
            'idx-leave-approval-policy-requestedTo',
            'leave_approval_policy',
            'requestedTo'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-leave-approval-policy-requestedTo',
            'leave_approval_policy',
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
        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-leave-approval-policy-employeeId',
            'leave_approval_policy',
        );

        // drop index for column `employeeId`
        $this->dropIndex(
            'idx-leave-approval-policy-employeeId',
            'leave_approval_policy',
        );

        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-leave-approval-policy-requestedTo',
            'leave_approval_policy',
        );

        // drop index for column `requestedTo`
        $this->dropIndex(
            'idx-leave-approval-policy-requestedTo',
            'leave_approval_policy',
        );

        $this->dropTable('{{%leave_approval_policy}}');
    }
}
