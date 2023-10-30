<?php

use app\modules\sale\models\ticket\Ticket;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ticket}}`.
 */
class m231030_203500_add_flightStatus_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Ticket::tableName(), 'flightStatus', $this->string(50)->notNull()->defaultValue('Issued'));
        $this->createIndex('idx-ticket-flightStatus', Ticket::tableName(), 'flightStatus');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-ticket-flightStatus', Ticket::tableName());
        $this->dropColumn(Ticket::tableName(), 'flightStatus');
    }
}
