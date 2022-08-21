<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%supplier}}`.
 */
class m220820_134827_add_categories_column_to_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%supplier}}', 'categories', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%supplier}}', 'categories');
    }
}
