<?php

use app\modules\sale\models\ticket\Ticket;
use yii\db\Migration;

/**
 * Class m230706_065145_alter_tripType_column_to_ticket_table
 */
class m230706_065145_alter_tripType_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(Ticket::tableName(), 'tripType', "ENUM('One Way','Return', 'Multi City', 'Others') DEFAULT NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230706_065145_alter_tripType_column_to_ticket_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230706_065145_alter_tripType_column_to_ticket_table cannot be reverted.\n";

        return false;
    }
    */
}
