<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%holiday_supplier}}`.
 */
class m221017_184012_add_motherId_column_to_ticket_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket_supplier}}', 'motherId', $this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ticket_supplier}}', 'motherId');
    }
}
