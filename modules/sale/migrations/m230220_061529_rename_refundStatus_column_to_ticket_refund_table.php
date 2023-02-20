<?php

use app\modules\sale\models\ticket\TicketRefund;
use yii\db\Migration;

/**
 * Class m230220_061529_rename_refundStatus_column_to_ticket_refund_table
 */
class m230220_061529_rename_refundStatus_column_to_ticket_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn(TicketRefund::tableName(), 'refundStatus', 'refundType');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230220_061529_rename_refund_status_column_to_ticket_refund_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230220_061529_rename_refund_status_column_to_ticket_refund_table cannot be reverted.\n";

        return false;
    }
    */
}
