<?php

use app\modules\account\models\AdvancePayment;
use yii\db\Migration;

/**
 * Class m231030_100341_add_column_agencyId_to_advance_payment_table
 */
class m231030_100341_add_column_agencyId_to_advance_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(AdvancePayment::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->createIndex('idx-advance-payment-agencyId', AdvancePayment::tableName(), 'agencyId');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-advance-payment-agencyId', AdvancePayment::tableName());
        $this->dropColumn(AdvancePayment::tableName(), 'agencyId');

        echo "m231030_100341_add_column_agencyId_to_advance_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231030_100341_add_column_agencyId_to_advance_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
