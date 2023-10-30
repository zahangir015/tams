<?php

use app\modules\account\models\AdvancePayment;
use yii\db\Migration;

/**
 * Class m231029_184645_add_column_identificationNumber_to_advance_payment_table
 */
class m231029_184645_add_column_identificationNumber_to_advance_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(AdvancePayment::tableName(), 'identificationNumber', $this->string(32)->notNull());
        $this->createIndex('idx-advance-payment-identificationNumber', AdvancePayment::tableName(), 'identificationNumber');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-advance-payment-identificationNumber', AdvancePayment::tableName());
        echo "m231029_184645_add_column_identificationNumber_to_advance_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231029_184645_add_column_identificationNumber_to_advance_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
