<?php

use app\modules\sale\models\ticket\Ticket;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%ticket}}`.
 */
class m230731_172900_add_referenceCommission_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Ticket::tableName(), 'referenceCommission', $this->double()->null()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Ticket::tableName(), 'referenceCommission');
    }
}
