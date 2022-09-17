<?php

use yii\db\Migration;

/**
 * Class m220917_175706_alter_issueDate_column_to_ticket_supplier_table
 */
class m220917_175706_alter_issueDate_column_to_ticket_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('ticket_supplier', 'issueDate', $this->date()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220917_175706_alter_issueDate_column_to_ticket_supplier_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220917_175706_alter_issueDate_column_to_ticket_supplier_table cannot be reverted.\n";

        return false;
    }
    */
}
