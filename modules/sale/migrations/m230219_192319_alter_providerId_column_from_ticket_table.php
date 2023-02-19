<?php

use app\modules\sale\models\ticket\Ticket;
use yii\db\Migration;

/**
 * Class m230219_192319_alter_providerId_column_from_ticket_table
 */
class m230219_192319_alter_providerId_column_from_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(Ticket::tableName(), 'providerId', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230219_192319_alter_providerId_column_from_ticket_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230219_192319_alter_providerId_column_from_ticket_table cannot be reverted.\n";

        return false;
    }
    */
}
