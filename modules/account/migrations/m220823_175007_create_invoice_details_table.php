<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice_details}}`.
 */
class m220823_175007_create_invoice_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice_details}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%invoice_details}}');
    }
}
