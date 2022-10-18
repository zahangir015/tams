<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hotel_supplier}}`.
 */
class m221017_184814_add_motherId_column_to_hotel_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hotel_supplier}}', 'motherId', $this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hotel_supplier}}', 'motherId');
    }
}
