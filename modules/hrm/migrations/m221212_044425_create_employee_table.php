<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee}}`.
 */
class m221212_044425_create_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'userId' => $this->integer()->null(),
            'reportTo' => $this->integer()->null(),
            'firstName' => $this->string()->notNull(),
            'lastName' => $this->string()->notNull(),
            'fathersName' => $this->string()->null(),
            'mothersName' => $this->string()->null(),
            'dateOfBirth' => $this->date()->notNull(),
            'gender' => $this->boolean()->notNull(),
            'bloodGroup' => $this->string()->null(),
            'maritalStatus' => $this->boolean()->null(),
            'religion' => "ENUM('Islam','Hindu','Buddhist','Christian')",
            'nid' => $this->string()->notNull(),
            'officialId' => $this->string()->notNull(),
            'officialEmail' => $this->string()->null(),
            'officialPhone' => $this->string()->null(),
            'permanentAddress' => $this->string()->notNull(),
            'presentAddress' => $this->string()->notNull(),
            'personalEmail' => $this->string(100)->null(),
            'personalPhone' => $this->string(100)->notNull(),
            'contactPersonsName' => $this->string()->null(),
            'contactPersonsPhone' => $this->string()->null(),
            'contactPersonsAddress' => $this->string()->null(),
            'contactPersonsRelation' => $this->string()->null(),
            'joiningDate' => $this->date()->notNull(),
            'confirmationDate' => $this->date()->null(),
            'inProhibition' => $this->boolean()->defaultValue(0),
            'jobCategory' => $this->string()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `userId`
        $this->createIndex(
            'idx-employee-userId',
            'employee',
            'userId'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-employee-userId',
            'employee',
            'userId',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `reportTo`
        $this->createIndex(
            'idx-employee-reportTo',
            'employee',
            'reportTo'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-employee-userId',
            'employee'
        );

        // drops index for column `userId`
        $this->dropIndex(
            'idx-employee-userId',
            'employee'
        );

        // drops index for column `reportTo`
        $this->dropIndex(
            'idx-employee-reportTo',
            'employee'
        );

        $this->dropTable('{{%employee}}');
    }
}
