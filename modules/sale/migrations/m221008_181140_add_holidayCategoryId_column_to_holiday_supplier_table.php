<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%holiday_supplier}}`.
 */
class m221008_181140_add_holidayCategoryId_column_to_holiday_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('holiday_supplier', 'holidayCategoryId', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('holiday_supplier', 'holidayCategoryId');
    }
}
