<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payslip_type_detail}}`.
 */
class m230311_182856_create_payslip_type_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payslip_type_detail}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payslip_type_detail}}');
    }
}
