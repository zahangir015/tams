<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attendance}}`.
 */
class m230221_200323_create_attendance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attendance}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeeId' => $this->integer()->notNull(),
            'shiftId' => $this->integer()->notNull(),
            'leaveTypeId' => $this->integer()->null(),
            'leaveApplicationId' => $this->integer()->null(),
            'rosterId' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'entry' => $this->time()->notNull(),
            'exit' => $this->time()->null(),
            'isAbsent' => $this->boolean()->defaultValue(false),
            'isLate' => $this->boolean()->defaultValue(0),
            'isEarlyOut' => $this->boolean()->defaultValue(0),
            'totalLateInTime' => $this->time()->null(),
            'totalEarlyOutTime' => $this->time()->null(),
            'totalWorkingHours' => $this->time()->null(),
            'overTime' => $this->time()->null(),
            'remarks' => $this->string(255)->null(),
            'employeeNote' => $this->string(255)->null(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->null()
        ]);

        // creates index for column `employeeId`
        $this->createIndex(
            'idx-attendance-employeeId',
            'attendance',
            'employeeId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-attendance-employeeId',
            'attendance',
            'employeeId',
            'employee',
            'id',
            'CASCADE'
        );

        // creates index for column `shiftId`
        $this->createIndex(
            'idx-attendance-shiftId',
            'attendance',
            'shiftId'
        );

        // add foreign key for table `shift`
        $this->addForeignKey(
            'fk-attendance-shiftId',
            'attendance',
            'shiftId',
            'shift',
            'id',
            'CASCADE'
        );

        // creates index for column `leaveTypeId`
        $this->createIndex(
            'idx-attendance-leaveTypeId',
            'attendance',
            'leaveTypeId'
        );

        // add foreign key for table `leave_type`
        $this->addForeignKey(
            'fk-attendance-leaveTypeId',
            'attendance',
            'leaveTypeId',
            'leave_type',
            'id',
            'CASCADE'
        );

        // creates index for column `leaveApplicationId`
        $this->createIndex(
            'idx-attendance-leaveApplicationId',
            'attendance',
            'leaveApplicationId'
        );

        // add foreign key for table `leave_application`
        $this->addForeignKey(
            'fk-attendance-leaveApplicationId',
            'attendance',
            'leaveApplicationId',
            'leave_application',
            'id',
            'CASCADE'
        );

        // creates index for column `rosterId`
        $this->createIndex(
            'idx-attendance-rosterId',
            'attendance',
            'rosterId'
        );

        // add foreign key for table `roster`
        $this->addForeignKey(
            'fk-attendance-rosterId',
            'attendance',
            'rosterId',
            'roster',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `employee`
        $this->dropForeignKey(
            'fk-attendance-employeeId',
            'attendance'
        );

        // drops index for column `employeeId`
        $this->dropIndex(
            'idx-attendance-employeeId',
            'attendance'
        );

        // drops foreign key for table `shift`
        $this->dropForeignKey(
            'fk-attendance-shiftId',
            'attendance'
        );

        // drops index for column `shiftId`
        $this->dropIndex(
            'idx-attendance-shiftId',
            'attendance'
        );

        // drops foreign key for table `leave_type`
        $this->dropForeignKey(
            'fk-attendance-leaveTypeId',
            'attendance'
        );

        // drops index for column `leaveTypeId`
        $this->dropIndex(
            'idx-attendance-leaveTypeId',
            'attendance'
        );

        // drops foreign key for table `leave_application`
        $this->dropForeignKey(
            'fk-attendance-leaveApplicationId',
            'attendance'
        );

        // drops index for column `leaveApplicationId`
        $this->dropIndex(
            'idx-attendance-leaveApplicationId',
            'attendance'
        );

        // drops foreign key for table `roster`
        $this->dropForeignKey(
            'fk-attendance-rosterId',
            'attendance'
        );

        // drops index for column `rosterId`
        $this->dropIndex(
            'idx-attendance-rosterId',
            'attendance'
        );

        $this->dropTable('{{%attendance}}');
    }
}
