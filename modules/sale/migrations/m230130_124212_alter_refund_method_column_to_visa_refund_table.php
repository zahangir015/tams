<?php

use app\modules\sale\models\visa\VisaRefund;
use yii\db\Migration;

/**
 * Class m230130_124212_alter_refund_method_column_to_visa_refund_table
 */
class m230130_124212_alter_refund_method_column_to_visa_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(VisaRefund::tableName(), 'refundMethod', "ENUM('Credit/Debit Card','Bank Account','Refund Adjustment', 'Cash') DEFAULT NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230130_124212_alter_refund_method_column_to_visa_refund_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230130_124212_alter_refund_method_column_to_visa_refund_table cannot be reverted.\n";

        return false;
    }
    */
}
