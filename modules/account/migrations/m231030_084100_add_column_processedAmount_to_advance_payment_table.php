<?php

use app\modules\account\models\AdvancePayment;
use yii\db\Migration;

/**
 * Class m231030_084100_add_column_processedAmount_to_advance_payment_table
 */
class m231030_084100_add_column_processedAmount_to_advance_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(AdvancePayment::tableName(), 'processedAmount', $this->double()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(AdvancePayment::tableName(), 'processedAmount');
        echo "m231030_084100_add_column_processedAmount_to_advance_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231030_084100_add_column_processedAmount_to_advance_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
