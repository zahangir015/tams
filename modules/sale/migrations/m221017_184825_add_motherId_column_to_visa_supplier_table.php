<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%visa_supplier}}`.
 */
class m221017_184825_add_motherId_column_to_visa_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%visa_supplier}}', 'motherId', $this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%visa_supplier}}', 'motherId');
    }
}
