<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ledger}}`.
 */
class m220823_175112_create_ledger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ledger}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'title' => $this->string(150)->notNull(),
            'date' => $this->date()->notNull(),
            'reference' => $this->string()->null(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'subRefId' => $this->integer()->null(),
            'subRefModel' => $this->string(150)->null(),
            'debit' => $this->double()->defaultValue(0),
            'credit' => $this->double()->defaultValue(0),
            'balance' => $this->double()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `date`
        $this->createIndex(
            'idx-ledger-date',
            'ledger',
            'date'
        );

        // creates index for column `refId`
        $this->createIndex(
            'idx-ledger-refId',
            'ledger',
            'refId'
        );

        // creates index for column `refModel`
        $this->createIndex(
            'idx-ledger-refModel',
            'ledger',
            'refModel'
        );

        // creates index for column `subRefId`
        $this->createIndex(
            'idx-ledger-subRefId',
            'ledger',
            'subRefId'
        );

        // creates index for column `subRefModel`
        $this->createIndex(
            'idx-ledger-subRefModel',
            'ledger',
            'subRefModel'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop index for column `date`
        $this->dropIndex(
            'idx-ledger-date',
            'ledger'
        );

        // drop index for column `refId`
        $this->dropIndex(
            'idx-ledger-refId',
            'ledger'
        );

        // drop index for column `refModel`
        $this->dropIndex(
            'idx-ledger-refModel',
            'ledger'
        );

        // drop index for column `subRefId`
        $this->dropIndex(
            'idx-ledger-subRefId',
            'ledger'
        );

        // drop index for column `subRefModel`
        $this->dropIndex(
            'idx-ledger-subRefModel',
            'ledger'
        );

        $this->dropTable('{{%ledger}}');
    }
}
