<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%journal}}`.
 */
class m230126_174225_create_journal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%journal}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'journalNumber' => $this->string()->notNull()->unique(),
            'postedDate' => $this->date()->notNull(),
            'debit' => $this->double()->defaultValue(0),
            'credit' => $this->double()->defaultValue(0),
            'outOfBalance' => $this->double()->defaultValue(0),
            'status' => $this->tinyInteger()->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedAt' => $this->integer(11)->null(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
        ]);

        // creates index for column `journalNo`
        $this->createIndex(
            'idx-journal-journalNumber',
            'journal',
            'journalNumber'
        );

        // creates index for column `postedDate`
        $this->createIndex(
            'idx-journal-postedDate',
            'journal',
            'postedDate'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `journalNumber`
        $this->dropIndex(
            'idx-journal-journalNumber',
            'journal'
        );

        // drops index for column `postedDate`
        $this->dropIndex(
            'idx-journal-postedDate',
            'journal'
        );

        $this->dropTable('{{%journal}}');
    }
}
