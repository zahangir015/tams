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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ledger}}');
    }
}
