<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ticket}}`.
 */
class m220916_171608_add_discount_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket}}', 'discount', $this->float()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ticket}}', 'discount');
    }
}
