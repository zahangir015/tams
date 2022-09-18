<?php

use app\modules\account\models\InvoiceDetail;
use yii\db\Migration;

/**
 * Class m220918_174025_alter_status_column_to_invoice_detail_table
 */
class m220918_174025_alter_status_column_to_invoice_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(InvoiceDetail::tableName(), 'status', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220918_174025_alter_status_column_to_invoice_detail_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220918_174025_alter_status_column_to_invoice_detail_table cannot be reverted.\n";

        return false;
    }
    */
}
