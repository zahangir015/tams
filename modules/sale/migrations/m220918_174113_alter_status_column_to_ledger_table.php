<?php

use app\modules\account\models\Ledger;
use yii\db\Migration;

/**
 * Class m220918_174113_alter_status_column_to_ledger_table
 */
class m220918_174113_alter_status_column_to_ledger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(Ledger::tableName(), 'status', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220918_174113_alter_status_column_to_ledger_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220918_174113_alter_status_column_to_ledger_table cannot be reverted.\n";

        return false;
    }
    */
}
