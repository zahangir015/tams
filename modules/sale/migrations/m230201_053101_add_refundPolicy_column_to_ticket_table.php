<?php

use app\modules\sale\models\ticket\Ticket;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ticket}}`.
 */
class m230201_053101_add_refundPolicy_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Ticket::tableName(), 'refundPolicy', "ENUM('Refundable & Changeable', 'Non-Refundable & Non Changeable', 'Non-refundable but Changeable')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Ticket::tableName(), 'refundPolicy');
    }
}
