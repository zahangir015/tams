<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_refund}}`.
 */
class m220823_174938_create_ticket_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket_refund}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ticket_refund}}');
    }
}
