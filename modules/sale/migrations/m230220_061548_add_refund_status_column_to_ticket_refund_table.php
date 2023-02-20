<?php

use app\modules\sale\models\ticket\TicketRefund;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ticket_refund}}`.
 */
class m230220_061548_add_refund_status_column_to_ticket_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(TicketRefund::tableName(), 'refundStatus', "ENUM('Refund Submitted', 'Refund Received', 'Refund Paid', 'Refund Adjusted')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(TicketRefund::tableName(), 'refundStatus');
    }
}
