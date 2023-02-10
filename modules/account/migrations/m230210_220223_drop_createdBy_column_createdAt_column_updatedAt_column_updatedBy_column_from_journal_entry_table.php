<?php

use app\modules\account\models\JournalEntry;
use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%journal_entry}}`.
 */
class m230210_220223_drop_createdBy_column_createdAt_column_updatedAt_column_updatedBy_column_from_journal_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(JournalEntry::tableName(), 'createdAt');
        $this->dropColumn(JournalEntry::tableName(), 'createdBy');
        $this->dropColumn(JournalEntry::tableName(), 'updatedAt');
        $this->dropColumn(JournalEntry::tableName(), 'updatedBy');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(JournalEntry::tableName(), 'createdAt', $this->integer()->notNull());
        $this->addColumn(JournalEntry::tableName(), 'createdBy', $this->integer(11)->notNull());
        $this->addColumn(JournalEntry::tableName(), 'updatedAt', $this->integer()->null());
        $this->addColumn(JournalEntry::tableName(), 'updatedBy', $this->integer(11)->null());
    }
}
