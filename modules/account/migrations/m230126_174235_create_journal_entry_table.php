<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%journal_entry}}`.
 */
class m230126_174235_create_journal_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%journal_entry}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'journalId' => $this->integer()->notNull(),
            'accountId' => $this->integer()->notNull(),
            'accountName' => $this->string(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'debit' => $this->double()->defaultValue(0),
            'credit' => $this->double()->defaultValue(0),
            'details' => $this->string()->null(),
            'status' => $this->tinyInteger()->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'createdBy' => $this->integer(11)->notNull(),
            'updatedAt' => $this->integer()->null(),
            'updatedBy' => $this->integer(11)->null(),
        ]);

        // creates index for column `journalId`
        $this->createIndex(
            'idx-journal-entry-journalId',
            'journal_entry',
            'journalId'
        );

        // add foreign key for table `journal`
        $this->addForeignKey(
            'fk-journal-entry-journalId',
            'journal_entry',
            'journalId',
            'journal',
            'id',
            'CASCADE'
        );

        // creates index for column `accountName`
        $this->createIndex(
            'idx-journal-entry-accountName',
            'journal_entry',
            'accountName'
        );

        // creates index for column `accountId`
        $this->createIndex(
            'idx-journal-entry-accountId',
            'journal_entry',
            'accountId'
        );

        // add foreign key for table `chart_of_account`
        $this->addForeignKey(
            'fk-journal-entry-accountId',
            'journal_entry',
            'accountId',
            'chart_of_account',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `accountName`
        $this->dropIndex(
            'idx-journal-entry-accountName',
            'journal_entry'
        );

        // drops index for column `journalId`
        $this->dropIndex(
            'idx-journal-entry-journalId',
            'journal_entry'
        );

        // drops foreign key for table `journal_entry`
        $this->dropForeignKey(
            'fk-journal-entry-journalId',
            'journal_entry'
        );

        // drops index for column `accountId`
        $this->dropIndex(
            'idx-journal-entry-accountId',
            'journal_entry'
        );

        // drops foreign key for table `journal_entry`
        $this->dropForeignKey(
            'fk-journal-entry-accountId',
            'journal_entry'
        );

        $this->dropTable('{{%journal_entry}}');
    }
}
