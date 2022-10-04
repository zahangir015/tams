<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%holiday_refund}}`.
 */
class m221003_183553_create_holiday_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%holiday_refund}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%holiday_refund}}');
    }
}
