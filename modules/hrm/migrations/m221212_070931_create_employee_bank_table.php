<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_bank}}`.
 */
class m221212_070931_create_employee_bank_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_bank}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee_bank}}');
    }
}
