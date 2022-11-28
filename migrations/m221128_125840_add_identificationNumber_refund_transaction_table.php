<?php

use yii\db\Migration;

/**
 * Class m221128_125840_add_identificationNumber_refund_transaction_table
 */
class m221128_125840_add_identificationNumber_refund_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%refund_transaction}}', 'identificationNumber', $this->string(36));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%refund_transaction}}', 'identificationNumber');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221128_125840_add_identificationNumber_refund_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
