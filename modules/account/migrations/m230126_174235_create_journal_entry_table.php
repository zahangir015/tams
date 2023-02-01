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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%journal_entry}}');
    }
}
