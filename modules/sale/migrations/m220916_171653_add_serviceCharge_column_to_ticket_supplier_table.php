<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ticket_supplier}}`.
 */
class m220916_171653_add_serviceCharge_column_to_ticket_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket_supplier}}', 'serviceCharge', $this->float()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ticket_supplier}}', 'serviceCharge');
    }
}
