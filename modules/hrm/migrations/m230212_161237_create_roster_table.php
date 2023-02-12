<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%roster}}`.
 */
class m230212_161237_create_roster_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%roster}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'departmentId' => $this->integer(11)->notNull(),
            'employeeId' => $this->integer(11)->notNull(),
            'shiftId' => $this->integer(11)->notNull(),
            'rosterDate' => $this->date()->notNull(),
            'alternativeHoliday' => $this->date()->notNull(),
            'remarks' => $this->string()->null(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `departmentId`
        $this->createIndex(
            'idx-roster-departmentId',
            'roster',
            'departmentId'
        );

        // add foreign key for table `department`
        $this->addForeignKey(
            'fk-roster-departmentId',
            'roster',
            'departmentId',
            'department',
            'id',
            'CASCADE'
        );

        // creates index for column `shiftId`
        $this->createIndex(
            'idx-roster-shiftId',
            'roster',
            'shiftId'
        );

        // add foreign key for table `shift`
        $this->addForeignKey(
            'fk-roster-shiftId',
            'roster',
            'shiftId',
            'shift',
            'id',
            'CASCADE'
        );

        // creates index for column `employeeId`
        $this->createIndex(
            'idx-roster-employeeId',
            'roster',
            'employeeId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-roster-employeeId',
            'roster',
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
        // drop foreign key for table `department`
        $this->dropForeignKey(
            'fk-roster-departmentId',
            'roster',
        );

        // drop index for column `departmentId`
        $this->dropIndex(
            'idx-roster-departmentId',
            'roster',
        );


        // drop foreign key for table `shift`
        $this->dropForeignKey(
            'fk-roster-shiftId',
            'roster',
        );

        // drop index for column `shiftId`
        $this->dropIndex(
            'idx-roster-shiftId',
            'roster',
        );

        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-roster-employeeId',
            'roster',
        );

        // drop index for column `employeeId`
        $this->dropIndex(
            'idx-roster-employeeId',
            'roster',
        );

        $this->dropTable('{{%roster}}');
    }
}
