<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_supplier}}`.
 */
class m220823_174436_create_ticket_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket_supplier}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ticket_supplier}}');
    }
}
