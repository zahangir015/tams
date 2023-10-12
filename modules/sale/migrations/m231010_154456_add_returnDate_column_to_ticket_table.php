<?php

use app\modules\sale\models\ticket\Ticket;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ticket}}`.
 */
class m231010_154456_add_returnDate_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Ticket::tableName(), 'returnDate', $this->date()->null());
        $this->createIndex('idx-ticket-returnDate', Ticket::tableName(), 'returnDate');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-ticket-returnDate', Ticket::tableName());
        $this->dropColumn(Ticket::tableName(), 'returnDate');
    }
}
