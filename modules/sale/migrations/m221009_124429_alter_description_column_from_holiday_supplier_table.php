<?php

use yii\db\Migration;

/**
 * Class m221009_124429_alter_description_column_from_holiday_supplier_table
 */
class m221009_124429_alter_description_column_from_holiday_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('holiday_supplier', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221009_124429_alter_description_column_from_holiday_supplier_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221009_124429_alter_description_column_from_holiday_supplier_table cannot be reverted.\n";

        return false;
    }
    */
}
