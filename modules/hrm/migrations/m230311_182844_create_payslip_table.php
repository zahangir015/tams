<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payslip}}`.
 */
class m230311_182844_create_payslip_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payslip}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payslip}}');
    }
}
