<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier}}`.
 */
class m220805_181902_create_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplier}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%supplier}}');
    }
}
