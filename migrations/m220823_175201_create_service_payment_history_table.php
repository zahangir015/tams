<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%service_payment_history}}`.
 */
class m220823_175201_create_service_payment_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%service_payment_history}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%service_payment_history}}');
    }
}
