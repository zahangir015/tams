<?php

use app\modules\sale\models\ticket\TicketRefund;
use yii\db\Migration;

/**
 * Class m230226_171216_alter_refId_column_refModel_column_to_ticket_refund_table
 */
class m230226_171216_alter_refId_column_refModel_column_to_ticket_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('refId', TicketRefund::tableName(), 'refId');
        $this->createIndex('refModel', TicketRefund::tableName(), 'refModel');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230226_171216_alter_refId_column_refModel_column_to_ticket_refund_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230226_171216_alter_refId_column_refModel_column_to_ticket_refund_table cannot be reverted.\n";

        return false;
    }
    */
}
