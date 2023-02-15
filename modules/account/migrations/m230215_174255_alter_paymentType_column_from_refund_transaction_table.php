<?php

use yii\db\Migration;

/**
 * Class m230215_174255_alter_paymentType_column_from_refund_transaction_table
 */
class m230215_174255_alter_paymentType_column_from_refund_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn(\app\modules\account\models\RefundTransaction::tableName(), 'PaymentType', 'paymentType');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230215_174255_alter_paymentType_column_from_refund_transaction_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230215_174255_alter_paymentType_column_from_refund_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
